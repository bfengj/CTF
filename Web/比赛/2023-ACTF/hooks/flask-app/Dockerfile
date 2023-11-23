# 使用官方 Python 镜像作为基础镜像
FROM python:3.7

# 设置工作目录
WORKDIR /app

# 复制当前目录中的所有文件到容器中的工作目录
COPY app.py .

RUN pip config set global.index-url https://pypi.tuna.tsinghua.edu.cn/simple

# 安装 Flask 和 requests 依赖
RUN pip install Flask requests

# 暴露容器端口
EXPOSE 5000

# 启动 Flask 应用程序
CMD ["python", "app.py"]
