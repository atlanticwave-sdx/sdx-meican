#!/bin/sh

cp $MEICAN_DIR/docker_for_build/db.php $MEICAN_DIR/config/ \
 && cp /composer.phar $MEICAN_DIR \
 && sed -i "s/MYSQL_DATABASE/$MYSQL_DATABASE/" $MEICAN_DIR/config/db.php \
 && sed -i "s/MYSQL_USER/$MYSQL_USER/" $MEICAN_DIR/config/db.php \
 && sed -i "s/MYSQL_PASSWORD/$MYSQL_PASSWORD/" $MEICAN_DIR/config/db.php \
 && sed -i "s#XXX_MEICAN_URL_XXX#$MEICAN_HOST#g" $MEICAN_DIR/web/index.php \
 && sed -i "s#XXX_SDX_CONTROLLER_URL_XXX#$SDX_CONTROLLER_URL#g" $MEICAN_DIR/web/index.php \
 && sed -i "s#XXX_ORCID_CLIENT_ID_XXX#$ORCID_CLIENT_ID#g" $MEICAN_DIR/web/index.php \
 && sed -i "s#XXX_ORCID_CLIENT_SECRET_XXX#$ORCID_CLIENT_SECRET#g" $MEICAN_DIR/web/index.php \
 && sed -i "s#XXX_SMTP_HOST_XXX#$SMTP_HOST#g" $MEICAN_DIR/config/mailer.php \
 && sed -i "s#XXX_SMTP_PORT_XXX#$SMTP_PORT#g" $MEICAN_DIR/config/mailer.php \
 && sed -i "s#XXX_SMTP_USER_XXX#$SMTP_USER#g" $MEICAN_DIR/config/mailer.php \
 && sed -i "s#XXX_SMTP_PASS_XXX#$SMTP_PASS#g" $MEICAN_DIR/config/mailer.php \
 && chown meican:meican $MEICAN_DIR/config/db.php  \
 && mkdir -p $MEICAN_DIR/vendor \
 && chmod 777 $MEICAN_DIR/vendor $MEICAN_DIR/web/assets $MEICAN_DIR/runtime \
 && su meican -c "php composer.phar install" \
 && service apache2 start \
 && tail -f /var/log/apache2/access.log
