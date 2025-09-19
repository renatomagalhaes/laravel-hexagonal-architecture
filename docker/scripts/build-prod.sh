#!/bin/bash

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Building production environment...${NC}"

# Construir container de produção
echo -e "${YELLOW}📦 Building production container...${NC}"
docker build -f docker/Dockerfile.prod -t laravel-hexagonal-prod .

echo -e "${GREEN}✅ Production build completed!${NC}"
echo -e "${YELLOW}🐳 Image tagged as: laravel-hexagonal-prod${NC}"
echo -e "${YELLOW}🚀 Ready for deployment!${NC}"
