FROM node:latest

RUN apt-get update && apt-get install -y \
    git \
    curl \
    wget \
    bash \
    && rm -rf /var/lib/apt/lists/*

RUN npm install -g @vue/cli @vue/cli-service

WORKDIR /app

EXPOSE 8080 3000 5173 13714

CMD ["tail", "-f", "/dev/null"]