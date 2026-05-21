#!/bin/bash
# =============================================================
# BrewCo - INITIAL SETUP
# Clones the repo, reorganizes the structure, builds images,
# uploads them to Harbor and starts the containers.
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
VITE_API_URL="http://grup3.infla.cat/brewco/api"

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
#    repo/
#      frontend/   → ~/BrewCo/frontend/
#      backend/    → ~/BrewCo/backend/
#      deployment/ → ~/BrewCo/deployment/   (Dockerfiles, configs)
# --------------------------------------------------
log "Reorganizing folder structure..."

# Frontend
rm -rf "${BREWCO_DIR}/frontend"
mv "${BREWCO_DIR}/repo/frontend" "${BREWCO_DIR}/frontend"

# Backend
rm -rf "${BREWCO_DIR}/backend"
mv "${BREWCO_DIR}/repo/backend"  "${BREWCO_DIR}/backend"

# Deployment (Dockerfiles and configs)
rm -rf "${BREWCO_DIR}/deployment"
mv "${BREWCO_DIR}/repo/deployment" "${BREWCO_DIR}/deployment"

# Remove cloned repo folder (we no longer need it)
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
# 5. Build Database image (MySQL + project SQL)
# --------------------------------------------------
log "=== BUILD: MySQL Database ==="
# Copy project SQL into the database Dockerfile context
cp "${BREWCO_DIR}/backend/database/sql/brewco.sql" \
   "${BREWCO_DIR}/deployment/database/brewco.sql" \
   || err "backend/database/sql/brewco.sql not found"

docker build \
  -t "${IMAGE_DATABASE}:latest" \
  -f "${BREWCO_DIR}/deployment/database/Dockerfile" \
  "${BREWCO_DIR}/deployment/database/"
log "Database image built."

# --------------------------------------------------
# 6. Build Backend Laravel image
# --------------------------------------------------
log "=== BUILD: Laravel Backend ==="
docker build \
  -t "${IMAGE_BACKEND}:latest" \
  -f "${BREWCO_DIR}/deployment/backend/Dockerfile" \
  "${BREWCO_DIR}/"
log "Backend image built."

# --------------------------------------------------
# 7. Build Frontend React image
# --------------------------------------------------
log "=== BUILD: React Frontend ==="
docker build \
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
# 10. Docker network and volume
# --------------------------------------------------
log "Creating Docker network and volume..."
docker network inspect brewco-net >/dev/null 2>&1 \
  || docker network create brewco-net
docker volume  inspect brewco-mysql-data >/dev/null 2>&1 \
  || docker volume create brewco-mysql-data
log "Network 'brewco-net' and volume 'brewco-mysql-data' ready."

# --------------------------------------------------
# 11. Stop and remove previous containers
# --------------------------------------------------
log "Cleaning previous containers (if they exist)..."
for name in brewco-database brewco-backend brewco-frontend; do
  if docker ps -a --format '{{.Names}}' | grep -q "^${name}$"; then
    warn "  Stopping and removing: ${name}"
    docker stop "${name}" && docker rm "${name}"
  fi
done

# --------------------------------------------------
# 12. Pull from Harbor and create containers
# --------------------------------------------------
log "=== PULL from Harbor ==="
docker pull "${IMAGE_DATABASE}:latest"
docker pull "${IMAGE_BACKEND}:latest"
docker pull "${IMAGE_FRONTEND}:latest"

log "=== Containers: Creating ==="

# --- Database ---
docker run -d \
  --name brewco-database \
  --network brewco-net \
  --restart unless-stopped \
  -v brewco-mysql-data:/var/lib/mysql \
  --memory="350Mi" \
  --memory-reservation="250Mi" \
  --cpus="0.4" \
  "${IMAGE_DATABASE}:latest"
log "  ✓ brewco-database created"

# Wait for MySQL to be ready before starting backend
log "Waiting for MySQL initialization (20s)..."
sleep 20

# --- Backend ---
docker run -d \
  --name brewco-backend \
  --network brewco-net \
  --restart unless-stopped \
  -p 8000:8000 \
  --memory="350Mi" \
  --memory-reservation="250Mi" \
  --cpus="0.4" \
  "${IMAGE_BACKEND}:latest"
log "  ✓ brewco-backend created"

# --- Frontend ---
docker run -d \
  --name brewco-frontend \
  --network brewco-net \
  --restart unless-stopped \
  -p 80:80 \
  --memory="250Mi" \
  --memory-reservation="150Mi" \
  --cpus="0.2" \
  "${IMAGE_FRONTEND}:latest"
log "  ✓ brewco-frontend created"

# --------------------------------------------------
# 13. Final status
# --------------------------------------------------
echo ""
log "============================================="
log " SETUP COMPLETED"
log "============================================="
echo ""
echo -e "${GREEN}Active containers:${NC}"
docker ps --filter "name=brewco-" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
echo ""
echo -e "${GREEN}Access URLs:${NC}"
echo "  Frontend : http://grup3.infla.cat/brewco"
echo "  API      : http://grup3.infla.cat/brewco/api"
echo ""
echo -e "${YELLOW}To update when there are changes on GitHub:${NC}"
echo "  bash ~/BrewCo/update.sh"