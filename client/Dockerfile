FROM node:lts

WORKDIR /var/www/client

COPY package-lock.json .
COPY package.json .

RUN npm install

COPY . .

CMD ["npm", "run", "dev"]