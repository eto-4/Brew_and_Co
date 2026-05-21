#!/bin/bash
# =============================================================
# BrewCo - MANUAL UPDATE
# Pulls the latest changes from GitHub, rebuilds
# the images and updates the Kubernetes deployments.
#
# Usage: bash ~/BrewCo/update.sh [frontend|backend|database|all]
# Examples:
#   bash update.sh           → updates everything (same as 'all')
#   bash update.sh frontend  → only rebuilds the frontend
#   bash update.sh backend   → only rebuilds the backend
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
TARGET="${1:-all}"

# --------------------------------------------------
# Colors
# --------------------------------------------------
GREEN='\033[0;32m'; YELLOW='\033[1;33m'; RED='\033[0;31m'; NC='\033[0m'
log()  { echo -e "${GREEN}[INFO]${NC}  $1"; }
warn() { echo -e "${YELLOW}[WARN]${NC}  $1"; }
err()  { echo -e "${RED}[ERROR]${NC} $1"; exit 1; }

log "============================================="
log " BrewCo - Update [${TARGET}]"
log "============================================="

cd "${BREWCO_DIR}" || err "${BREWCO_DIR} does not exist. Run setup.sh first"

# --------------------------------------------------
# 1. Pull changes from GitHub
# --------------------------------------------------
log "Downloading latest changes from GitHub..."
rm -rf /tmp/brewco-update
git clone "${GITHUB_REPO}" /tmp/brewco-update

update_folder() {
  local folder="$1"
  if [ "${TARGET}" = "all" ] || [ "${TARGET}" = "${folder}" ] || [ "${folder}" = "deployment" ]; then
    log "  Updating /${folder}..."
    rm -rf "${BREWCO_DIR}/${folder}"
    mv "/tmp/brewco-update/${folder}" "${BREWCO_DIR}/${folder}"
  fi
}
update_folder "frontend"
update_folder "backend"
update_folder "deployment"
rm -rf /tmp/brewco-update
log "Folders updated."

# --------------------------------------------------
# 2. Login to Harbor
# --------------------------------------------------
log "Logging into Harbor..."
echo "${HARBOR_PASS}" | docker login "${REGISTRY}" -u "${HARBOR_USER}" --password-stdin \
  || err "Harbor login failed"

# --------------------------------------------------
# Function: build + push + rollout restart
# --------------------------------------------------
rebuild_service() {
  local service="$1"
  local image="$2"

  log "=== BUILD: ${service} ==="
  case "${service}" in
    frontend)
      docker build \
        --no-cache \
        --build-arg VITE_API_URL="${VITE_API_URL}" \
        -t "${image}:latest" \
        -f "${BREWCO_DIR}/deployment/frontend/Dockerfile" \
        "${BREWCO_DIR}/"
      ;;
    backend)
      docker build \
        --no-cache \
        -t "${image}:latest" \
        -f "${BREWCO_DIR}/deployment/backend/Dockerfile" \
        "${BREWCO_DIR}/"
      ;;
    database)
      cp "${BREWCO_DIR}/backend/database/sql/brewco.sql" \
         "${BREWCO_DIR}/deployment/database/brewco.sql"
      docker build \
        --no-cache \
        -t "${image}:latest" \
        -f "${BREWCO_DIR}/deployment/database/Dockerfile" \
        "${BREWCO_DIR}/deployment/database/"
      ;;
  esac

  log "  Pushing to Harbor..."
  docker push "${image}:latest"

  log "  Removing local image..."
  docker rmi "${image}:latest"

  log "  Restarting Kubernetes deployment for ${service}..."
  case "${service}" in
    frontend)
      kubectl rollout restart deployment/brewco-frontend -n "${NAMESPACE}"
      kubectl rollout status  deployment/brewco-frontend -n "${NAMESPACE}" --timeout=120s \
        || warn "Frontend rollout timeout"
      ;;
    backend)
      kubectl rollout restart deployment/brewco-backend -n "${NAMESPACE}"
      kubectl rollout status  deployment/brewco-backend -n "${NAMESPACE}" --timeout=120s \
        || warn "Backend rollout timeout"
      ;;
    database)
      warn "DB image updated. The schema SQL only runs on first start (empty volume)."
      warn "For schema changes use Laravel migrations (php artisan migrate)."
      kubectl rollout restart statefulset/brewco-database -n "${NAMESPACE}"
      ;;
  esac

  log "  ✓ ${service} updated"
}

# --------------------------------------------------
# 3. Rebuild depending on target
# --------------------------------------------------
docker image prune -f

if [ "${TARGET}" = "all" ] || [ "${TARGET}" = "database" ]; then
  rebuild_service "database" "${IMAGE_DATABASE}"
fi
if [ "${TARGET}" = "all" ] || [ "${TARGET}" = "backend" ]; then
  rebuild_service "backend" "${IMAGE_BACKEND}"
fi
if [ "${TARGET}" = "all" ] || [ "${TARGET}" = "frontend" ]; then
  rebuild_service "frontend" "${IMAGE_FRONTEND}"
fi

# --------------------------------------------------
# 4. Final status
# --------------------------------------------------
echo ""
log "============================================="
log " UPDATE COMPLETED [${TARGET}]"
log "============================================="
echo ""
kubectl get pods -n "${NAMESPACE}"
echo ""
echo -e "${GREEN}URLs:${NC}"
echo "  Frontend : http://grup3.infla.cat/brewco"
echo "  API      : http://grup3.infla.cat/brewco/api"