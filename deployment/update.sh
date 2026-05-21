#!/bin/bash
# =============================================================
# BrewCo - MANUAL UPDATE
# Pulls the latest changes from GitHub, rebuilds
# the images and updates the containers without downtime.
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

TARGET="${1:-all}"  # What to update: frontend | backend | database | all

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
# 1. Pull changes from GitHub (clone into /tmp and move)
# --------------------------------------------------
log "Downloading latest changes from GitHub..."
rm -rf /tmp/brewco-update
git clone "${GITHUB_REPO}" /tmp/brewco-update
log "Repository updated."

# Function: updates a folder if the target includes it
update_folder() {
  local folder="$1"   # frontend | backend | deployment
  if [ "${TARGET}" = "all" ] || [ "${TARGET}" = "${folder}" ] || [ "${folder}" = "deployment" ]; then
    log "  Updating /${folder}..."
    rm -rf "${BREWCO_DIR}/${folder}"
    mv "/tmp/brewco-update/${folder}" "${BREWCO_DIR}/${folder}"
  fi
}

update_folder "frontend"
update_folder "backend"
update_folder "deployment"   # always updated (Dockerfiles and configs)

rm -rf /tmp/brewco-update
log "Folders updated."

# --------------------------------------------------
# 2. Login to Harbor
# --------------------------------------------------
log "Logging into Harbor..."
echo "${HARBOR_PASS}" | docker login "${REGISTRY}" -u "${HARBOR_USER}" --password-stdin \
  || err "Harbor login failed"

# --------------------------------------------------
# Function: build + push + replace container
# --------------------------------------------------
rebuild_service() {
  local service="$1"   # frontend | backend | database
  local image="$2"
  local container="brewco-${service}"

  log "=== BUILD: ${service} ==="

  case "${service}" in
    frontend)
      docker build \
        --build-arg VITE_API_URL="${VITE_API_URL}" \
        -t "${image}:latest" \
        -f "${BREWCO_DIR}/deployment/frontend/Dockerfile" \
        "${BREWCO_DIR}/frontend/"
      ;;
    backend)
      docker build \
        -t "${image}:latest" \
        -f "${BREWCO_DIR}/deployment/backend/Dockerfile" \
        "${BREWCO_DIR}/backend/"
      ;;
    database)
      cp "${BREWCO_DIR}/backend/database/sql/brewco.sql" \
         "${BREWCO_DIR}/deployment/database/brewco.sql"
      docker build \
        -t "${image}:latest" \
        -f "${BREWCO_DIR}/deployment/database/Dockerfile" \
        "${BREWCO_DIR}/deployment/database/"
      ;;
  esac

  log "  Pushing to Harbor..."
  docker push "${image}:latest"

  log "  Removing local image..."
  docker rmi "${image}:latest"

  log "  Replacing container ${container}..."
  docker stop  "${container}" 2>/dev/null && docker rm "${container}" 2>/dev/null || true
  docker pull  "${image}:latest"

  case "${service}" in
    frontend)
      docker run -d \
        --name "${container}" \
        --network brewco-net \
        --restart unless-stopped \
        -p 80:80 \
        "${image}:latest"
      ;;
    backend)
      docker run -d \
        --name "${container}" \
        --network brewco-net \
        --restart unless-stopped \
        -p 8000:8000 \
        "${image}:latest"
      ;;
    database)
      warn "  The DB is only reinitialized if the volume is empty."
      warn "  If you need schema changes, use Laravel migrations."
      docker run -d \
        --name "${container}" \
        --network brewco-net \
        --restart unless-stopped \
        -v brewco-mysql-data:/var/lib/mysql \
        "${image}:latest"
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
  log "Waiting for MySQL initialization (20s)..."
  sleep 20
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
docker ps --filter "name=brewco-" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
echo ""
echo -e "${GREEN}URLs:${NC}"
echo "  Frontend : http://grup3.infla.cat/brewco"
echo "  API      : http://grup3.infla.cat/brewco/api"