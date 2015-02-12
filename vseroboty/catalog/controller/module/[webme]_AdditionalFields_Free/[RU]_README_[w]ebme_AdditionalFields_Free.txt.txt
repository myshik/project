/*===============================================*\
|*
|* @charset: utf-8
|*
|* mod.name: [W]ebme AdditionalFields_Free
|*
|* mod.version: 0.1
|* release date: 2011.03.19
|*
|* Opencart version: 1.4.9.1+
|*
|* author: afwollis <afwollis@gmail.com>
|*
\*===============================================*/


##################################################################################################
# Краткое описание модуля
##############

Данный модуль позволяет добавлять дополнительные поля к товарам.
Любое количество, любые названия/значения.
Длина названия поля - от 1 до 64 символов.
Длина значения поля - от 1 до 255 символов.

Нет поддержки мультиязычности - т.е. как введете данные, так и будет на всех языках отображаться.

Нет русского языка.
Но фраз так мало и такие привычные, что начудить не получится.
Желающие могут перевести.

Данные дополнительных_полей хранятся в отдельной таблице.


##############
#
##################################################################################################


#################################################
# Новые файлы
##############

[=== ADMIN ===]
admin/controller/catalog/webme_additional_fields.php
admin/language/english/catalog/webme_additional_fields.php
admin/model/catalog/webme_additional_fields.php
admin/view/template/catalog/webme_additional_fields.tpl

admin/view/image/waf/waf_delete_active_32x32_red_iconza.png
admin/view/image/waf/waf_delete_inactive_32x32_grey_iconza.png
admin/view/image/waf/waf_remove_active_32x32_red_iconza.png
admin/view/image/waf/waf_remove_inactive_32x32_grey_iconza.png
admin/view/image/waf/waf_save_active_32x32_blue_iconza.png
admin/view/image/waf/waf_save_inactive_32x32_grey_iconza.png

[=== CATALOG ===]
catalog/controller/product/webme_additional_fields.php
catalog/language/english/product/webme_additional_fields.php
catalog/model/catalog/webme_additional_fields.php
catalog/view/theme/default/template/product/webme_additional_fields.tpl


#################################################
# Файлы, которые необходимо изменить
##############

[=== ADMIN ===]
admin/controller/catalog/product.php
admin/language/english/catalog/product.php
admin/view/template/catalog/product_form.tpl

[=== CATALOG ===]
catalog/view/theme/default/template/product/product.tpl


##################################################################################################
#
# УСТАНОВКА
#

################################
ШАГ --- 1 ---
##############

Заливаем файлы из папки "upload" в корень вашего магазина.


################################
ШАГ --- 2 ---
##############

[=== admin/controller/catalog/product.php ===]

Найдите функцию:
	===
	private function getForm() {
	===

После:
	===
		$this->data['tab_image'] = $this->language->get('tab_image');
	===

Добавьте:
	===
		/* webme - additional fields - part_#1  - start */
		$this->data['tab_waf'] = $this->language->get('tab_waf');
		/* webme - additional fields - part_#1  - end */
	===

########

ПЕРЕД:
	===
		$this->template = 'catalog/product_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	===

Вставьте:
	===
		/* webme - additional fields - part_#2  - start */
		$this->data['w_product_id'] = $product_info['product_id'];
		/* webme - additional fields - part_#2  - end */
	===


################################
ШАГ --- 3 ---
##############

[=== admin/language/english/catalog/product.php ===]

Вставьте:
	===
/* webme - additional fields - part_#1  - start */
$_['tab_waf'] = '[w] Additional Fields';
/* webme - additional fields - part_#1  - end */
	===


################################
ШАГ --- 4 ---
##############

[=== admin/view/template/catalog/product_form.tpl ===]

После:
	===
	<a tab="#tab_image"><?php echo $tab_image; ?></a>
	===

Добавьте:
	===
	<!-- webme - additional fields - part_#1 - start //--><a tab="#tab_waf"><?php echo $tab_waf; ?></a><!-- webme - additional fields - part_#1  - end //-->
	===

######## ТУТ ЖЕ

Мотаем вниз.
Находим:
	===
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
	===

Меняем на:
	===
    </form>
      <!-- webme - additional fields - part_#2 - start //-->
      <div id="tab_waf"></div>
      <!-- webme - additional fields - part_#2 - end //-->
  </div>
</div>
<!-- webme - additional fields - part_#3 - start //-->
<script type="text/javascript"><!--
$('#tab_waf').load('index.php?route=catalog/webme_additional_fields&product_id=<?php echo $w_product_id; ?>&token=<?php echo $token; ?>');

//--></script>
<!-- webme - additional fields - part_#3 - end //-->

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
	===


################################
ШАГ --- 5 ---
##############

[=== catalog/view/theme/default/template/product/product.tpl ===]

Примерно 198-ая строка - ПЕРЕД:
	===
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
	===

ИЛИ В ЛЮБОЕ ДРУГОЕ НУЖНОЕ ВАМ МЕСТО

Добавьте:
	===
    <!-- webme - additional fields - mod - part_#1  - start //-->
    <div id="webme_additional_fields"></div>
    <!-- webme - additional fields - mod - part_#1  - end //-->
	===

######## ТУТ ЖЕ

Примерно 216-ая строка - ПЕРЕД:
	===
<script type="text/javascript"><!--
	===

Добавьте:
	===
<!-- webme - additional fields - mod - part_#2  - start //-->
<script type="text/javascript"><!--
$('#webme_additional_fields').load('index.php?route=product/webme_additional_fields&product_id=<?php echo $product_id; ?>');
//--></script>
<!-- webme - additional fields - mod - part_#2  - end //-->
	===



###############################################################
###############################################################
###############################################################
#
# НУ ВОТ И ВСЕ, ВЫ СПРАВИЛИСЬ !
#
###############################################################
###############################################################
###############################################################



##################################################################################################
#
# CHANGELOG
#
##################################################################################################


[===2011.03.19===]
* v. 0.1
>> Первый релиз.
