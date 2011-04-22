cd "${app.path}/project/apps/${context}/daemon"
php win-consumer-daemon.php ../../../build/config/${properties.file.name} 1 ${queue.id} ${context}