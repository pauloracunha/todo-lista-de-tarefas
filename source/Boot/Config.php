<?php

date_default_timezone_set( "America/Sao_Paulo" );

/**
 * DATABASE
 */
define("CONF_DB_HOST", "localhost");
define("CONF_DB_USER", "root");
define("CONF_DB_PASS", "");
define("CONF_DB_NAME", "puzltodo");

/**
 * PROJECT URLs
 */
define("CONF_URL_BASE", "https://puzl.dev");
define("CONF_URL_TEST", "puzl.dev");

/**
 * SITE
 */
define("CONF_SITE_NAME", "Puzl ToDo");
define("CONF_SITE_TITLE", "Gerencie suas tarefas com o Puzl ToDo");
define("CONF_SITE_DESC",
    "O Puzl ToDo é um gerenciador de tarefas poderoso que vai te ajudar a controlar todas as tarefas do seu dia-a-dia!");
define("CONF_SITE_LANG", "pt_BR");
define("CONF_SITE_DOMAIN", "agenciabadoque.com.br");
define("CONF_SITE_ADDR_STREET", "Rua Inácio de Arruda");
define("CONF_SITE_ADDR_NUMBER", "46");
define("CONF_SITE_ADDR_COMPLEMENT", "");
define("CONF_SITE_ADDR_CITY", "Altinho");
define("CONF_SITE_ADDR_STATE", "PE");
define("CONF_SITE_ADDR_ZIPCODE", "55490-000");

/**
 * SOCIAL
 */
define("CONF_SOCIAL_TWITTER_CREATOR", "@pauloracunha");
define("CONF_SOCIAL_TWITTER_PUBLISHER", "@pauloracunha");
define("CONF_SOCIAL_FACEBOOK_APP", "5555555555");
define("CONF_SOCIAL_FACEBOOK_PAGE", "paulocunha");
define("CONF_SOCIAL_FACEBOOK_AUTHOR", "pauloracunha");
define("CONF_SOCIAL_GOOGLE_PAGE", "5555555555");
define("CONF_SOCIAL_GOOGLE_AUTHOR", "5555555555");
define("CONF_SOCIAL_INSTAGRAM_PAGE", "pauloracunha");
define("CONF_SOCIAL_YOUTUBE_PAGE", "pauloracunha");

/**
 * DATES
 */
define("CONF_DATE_BR", "d/m/Y H:i:s");
define("CONF_DATE_APP", "Y-m-d H:i:s");

/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);

/**
 * VIEW
 */
define("CONF_VIEW_PATH", __DIR__ . "/../../shared/views");
define("CONF_VIEW_EXT", "php");
define("CONF_VIEW_THEME", "todopuzl");

/**
 * UPLOAD
 */
define("CONF_UPLOAD_DIR", "storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_FILE_DIR", "files");
define("CONF_UPLOAD_MEDIA_DIR", "medias");

/**
 * IMAGES
 */
define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
define("CONF_IMAGE_SIZE", 2000);
define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

/**
 * MAIL
 */
define("CONF_MAIL_HOST", "");
define("CONF_MAIL_PORT", "");
define("CONF_MAIL_USER", "");
define("CONF_MAIL_PASS", "");
define("CONF_MAIL_SENDER", ["name" => "Puzl ToDo", "address" => "todo@puzl.com.br"]);
define("CONF_MAIL_SUPPORT", "sender@puzl.com.br");
define("CONF_MAIL_OPTION_LANG", "br");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_AUTH", true);
define("CONF_MAIL_OPTION_SECURE", "tls");
define("CONF_MAIL_OPTION_CHARSET", "utf-8");