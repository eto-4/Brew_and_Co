#!/bin/bash
# =============================================================
# BrewCo - INITIAL SETUP
# Clones the repo, reorganizes the structure, builds images,
# uploads them to Harbor and deploys to Kubernetes.
#
# Usage: bash setup.sh
# Only executed ONCE (or whenever you want to rebuild everything).
# For updates use: bash update.sh
# =============================================================
set -e

# --------------------------------------------------
# Configuration
# --------------------------------------------------
GITHUB_TOKEN="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"
GITHUB_REPO="https://${GITHUB_TOKEN}@github.com/eto-4/Brew_and_Co.git"
REGISTRY="kube0.lacetania.cat"
NAMESPACE="grup3"
HARBOR_USER="grup3"
HARBOR_PASS="HbFSeVLXLO1"
IMAGE_FRONTEND="${REGISTRY}/${NAMESPACE}/frontendg3"
IMAGE_BACKEND="${REGISTRY}/${NAMESPACE}/backendg3"
IMAGE_DATABASE="${REGISTRY}/${NAMESPACE}/databaseg3"
BREWCO_DIR=~/BrewCo
VITE_API_URL="http://grup3.infla.cat/brewco"

# --------------------------------------------------
# Colors
# --------------------------------------------------
GREEN='\033[0;32m'; YELLOW='\033[1;33m'; RED='\033[0;31m'; NC='\033[0m'
log()  { echo -e "${GREEN}[INFO]${NC}  $1"; }
warn() { echo -e "${YELLOW}[WARN]${NC}  $1"; }
err()  { echo -e "${RED}[ERROR]${NC} $1"; exit 1; }

log "============================================="
log " BrewCo - Initial setup"
log "============================================="

# --------------------------------------------------
# 1. Prepare ~/BrewCo directory
# --------------------------------------------------
log "Preparing directory ${BREWCO_DIR}..."
mkdir -p "${BREWCO_DIR}"
cd "${BREWCO_DIR}"

# --------------------------------------------------
# 2. Clone repository
# --------------------------------------------------
log "Cloning GitHub repository..."
if [ -d "${BREWCO_DIR}/repo" ]; then
  warn "A 'repo' folder already exists. Removing it for a clean clone..."
  rm -rf "${BREWCO_DIR}/repo"
fi
git clone "${GITHUB_REPO}" "${BREWCO_DIR}/repo"
log "Repository cloned."

# --------------------------------------------------
# 3. Reorganize structure
# --------------------------------------------------
log "Reorganizing folder structure..."
rm -rf "${BREWCO_DIR}/frontend"
mv "${BREWCO_DIR}/repo/frontend" "${BREWCO_DIR}/frontend"
rm -rf "${BREWCO_DIR}/backend"
mv "${BREWCO_DIR}/repo/backend"  "${BREWCO_DIR}/backend"
rm -rf "${BREWCO_DIR}/deployment"
mv "${BREWCO_DIR}/repo/deployment" "${BREWCO_DIR}/deployment"
rm -rf "${BREWCO_DIR}/repo"

log "Final structure:"
log "  ${BREWCO_DIR}/frontend/"
log "  ${BREWCO_DIR}/backend/"
log "  ${BREWCO_DIR}/deployment/"

# --------------------------------------------------
# 4. Harbor login
# --------------------------------------------------
log "Logging into Harbor (${REGISTRY})..."
echo "${HARBOR_PASS}" | docker login "${REGISTRY}" -u "${HARBOR_USER}" --password-stdin \
  || err "Harbor login failed"

# --------------------------------------------------
# 5. Build Database image
# --------------------------------------------------
log "=== BUILD: MySQL Database ==="
cp "${BREWCO_DIR}/backend/database/sql/brewco.sql" \
   "${BREWCO_DIR}/deployment/database/brewco.sql" \
   || err "backend/database/sql/brewco.sql not found"
docker build \
  --no-cache \
  -t "${IMAGE_DATABASE}:latest" \
  -f "${BREWCO_DIR}/deployment/database/Dockerfile" \
  "${BREWCO_DIR}/deployment/database/"
log "Database image built."

# --------------------------------------------------
# 6. Build Backend Laravel image
# --------------------------------------------------
log "=== BUILD: Laravel Backend ==="
docker build \
  --no-cache \
  -t "${IMAGE_BACKEND}:latest" \
  -f "${BREWCO_DIR}/deployment/backend/Dockerfile" \
  "${BREWCO_DIR}/"
log "Backend image built."

# --------------------------------------------------
# 7. Build Frontend React image
# --------------------------------------------------
log "=== BUILD: React Frontend ==="
docker build \
  --no-cache \
  --build-arg VITE_API_URL="${VITE_API_URL}" \
  -t "${IMAGE_FRONTEND}:latest" \
  -f "${BREWCO_DIR}/deployment/frontend/Dockerfile" \
  "${BREWCO_DIR}/"
log "Frontend image built."

# --------------------------------------------------
# 8. Push to Harbor
# --------------------------------------------------
log "=== PUSH: Uploading images to Harbor ==="
docker push "${IMAGE_DATABASE}:latest" && log "  ✓ database uploaded"
docker push "${IMAGE_BACKEND}:latest"  && log "  ✓ backend uploaded"
docker push "${IMAGE_FRONTEND}:latest" && log "  ✓ frontend uploaded"

# --------------------------------------------------
# 9. Remove local images
# --------------------------------------------------
log "=== Cleanup: Removing local images ==="
docker rmi "${IMAGE_DATABASE}:latest" "${IMAGE_BACKEND}:latest" "${IMAGE_FRONTEND}:latest"
docker image prune -f
log "Local images removed."

# --------------------------------------------------
# 10. Deploy to Kubernetes
# --------------------------------------------------
log "=== DEPLOY: Applying Kubernetes manifests ==="
kubectl apply -f "${BREWCO_DIR}/deployment/brewco.yml"
log "Manifests applied."

# Restart deployments to force pull of new images
log "Restarting deployments to pull latest images..."
kubectl rollout restart deployment/brewco-backend  -n "${NAMESPACE}" 2>/dev/null || true
kubectl rollout restart deployment/brewco-frontend -n "${NAMESPACE}" 2>/dev/null || true
kubectl rollout restart statefulset/brewco-database -n "${NAMESPACE}" 2>/dev/null || true

# Wait for rollout
log "Waiting for deployments to be ready..."
kubectl rollout status deployment/brewco-frontend -n "${NAMESPACE}" --timeout=120s || warn "Frontend rollout timeout"
kubectl rollout status deployment/brewco-backend  -n "${NAMESPACE}" --timeout=120s || warn "Backend rollout timeout"

# --------------------------------------------------
# 11. Final status
# --------------------------------------------------
echo ""
log "============================================="
log " SETUP COMPLETED"
log "============================================="
echo ""
echo -e "${GREEN}Kubernetes pods:${NC}"
kubectl get pods -n "${NAMESPACE}"
echo ""
echo -e "${GREEN}Services:${NC}"
kubectl get services -n "${NAMESPACE}"
echo ""
echo -e "${GREEN}Access URLs:${NC}"
echo "  Frontend : http://grup3.infla.cat/brewco"
echo "  API      : http://grup3.infla.cat/brewco/api"
echo ""
echo -e "${YELLOW}To update when there are changes on GitHub:${NC}"
echo "  bash ~/BrewCo/update.sh"