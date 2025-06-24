FROM python:3.11-slim
WORKDIR /bot
COPY requirements.txt ./
RUN apt-get update && apt-get install -y \
    chromium-driver \
    chromium \
    && rm -rf /var/lib/apt/lists/*
RUN pip install --no-cache-dir -r requirements.txt
COPY bot/ ./bot/
COPY bot/bot.py ./
CMD ["python", "bot.py"] 