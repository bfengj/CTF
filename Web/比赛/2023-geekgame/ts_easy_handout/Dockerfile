FROM denoland/deno:latest

EXPOSE 3000

WORKDIR /app
USER deno
COPY static ./static
COPY index.ts .
RUN deno cache index.ts
COPY flags/flag1 /flag1
COPY flags/flag2 /flag2

CMD ["run", "-A", "index.ts"]