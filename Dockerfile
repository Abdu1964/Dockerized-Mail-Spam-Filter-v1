FROM python:3.9

WORKDIR /app

# Copy the current directory contents into the container at /app
COPY . .
RUN pip install --default-timeout=600 --no-cache-dir -r requirements.txt
EXPOSE 5000
CMD ["python", "backend.py"]
