server {
  charset utf-8;
  client_max_body_size 128M;

  listen 80;
  root /var/www/dataprizma-plm/web;
  index index.php;
  server_name plm.loc;

  error_log  /var/log/nginx/error.log;
	access_log /var/log/nginx/access.log;

  location / {
    try_files $uri /index.php$is_args$args;
  }

  location ~ \.php$ {
    #try_files $uri = 404;
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
  	#fastcgi_pass 127.0.0.1:9000;
  	include fastcgi_params;
  	fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
  	fastcgi_param PATH_INFO $fastcgi_path_info;
  	fastcgi_param APPLICATION_ENV local;
  	include snippets/fastcgi-php.conf;
    fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
  }

  location ~ /\.ht {
    deny all;
  }
}