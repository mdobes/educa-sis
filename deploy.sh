REPO_PATH="/home/michal/educa-sis"

if [[ ! -d "${REPO_PATH}" ]]; then
  git clone https://github.com/mdobes/educa-sis "${REPO_PATH}"
fi

sudo docker exec web-educasis php artisan down --secret="secret-key"

cd "${REPO_PATH}" &&
  git clean -fd &&
  git checkout -- . &&
  git checkout main &&
  git reset --hard origin/main &&
  git pull

sudo docker-compose -f ${REPO_PATH}/docker-compose.yml up --build -d

sudo docker exec -e COMPOSER_MEMORY_LIMIT=-1 web-educasis composer install --no-scripts
sudo docker exec web-educasis composer dump-autoload --optimize --classmap-authoritative
sudo docker exec web-educasis php artisan cache:clear
sudo docker exec web-educasis php artisan route:clear
sudo docker exec web-educasis php artisan view:clear
sudo docker exec web-educasis php artisan migrate --force
sudo docker exec web-educasis php artisan ldap:import users --delete-missing

sudo chmod -R 0777 ${REPO_PATH}
