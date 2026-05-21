# ============================================================
# STAGE 1: Build - Install dependencies and generate build
# (source code is already in local context, no need to clone)
# ============================================================
FROM node:20-alpine AS builder

ARG VITE_API_URL=http://grup3.infla.cat/brewco/api

WORKDIR /app

# Copy frontend source code
COPY . .

# Install dependencies
RUN npm install

# Create .env with backend URL
RUN echo "VITE_API_URL=${VITE_API_URL}" > .env

# Production build
RUN npm run build

# ============================================================
# STAGE 2: Serve - Apache serving static build in /brewco
# ============================================================
FROM httpd:2.4-alpine

# Copy built files to Apache /brewco directory
COPY --from=builder /app/dist/ /usr/local/apache2/htdocs/brewco/

# Copy custom Apache configuration
COPY deployment/frontend/my-httpd.conf /usr/local/apache2/conf/httpd.conf

EXPOSE 80