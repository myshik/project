<?php
class ModelShippingEms extends Model {
	function getQuote($address) {
		$this->load->language('shipping/ems');
		
		if ($this->config->get('ems_status')) {
      		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "zone WHERE zone_id = '" . $this->config->get('config_zone_id') . "'");
			$city_from = $this->transl($query->row['name']);
			$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$address['zone_id'] . "'");
			$city_to = $this->transl($query->row['name']);
			$status = TRUE;
		} else {
			$status = FALSE;
		}
		$method_data = array();

		if ($status && ($this->config->get('ems_max_weight') >= number_format($this->cart->getWeight(), 1, '.', '') )) {
			$url = 'http://emspost.ru/api/rest/?method=ems.calculate&from=city--'.$city_from.'&to=city--'.$city_to.'&weight='.number_format($this->cart->getWeight(), 1, '.', '');
			$quote_data = array();
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch); 
			$response_array = json_decode($response, TRUE);
			curl_close($ch);

			if($response_array['rsp']['stat'] == 'ok') {
				$quote_data['ems'] = array(
					'id'           => 'ems.ems',
					'title'        => $response_array['rsp']['term']['min'].' - '.$response_array['rsp']['term']['max'].' '.$this->language->get('days'),
					'cost'         => $response_array['rsp']['price'],
					'tax_class_id' => 0,
					'text'         => $this->currency->format($response_array['rsp']['price'])
				);

				$method_data = array(
					'id'         => 'ems',
					'title'      => $this->language->get('text_title'),
					'quote'      => $quote_data,
					'sort_order' => $this->config->get('ems_sort_order'),
					'error'      => FALSE
				);
			}
		}
		return $method_data;
	}
	
	function transl($str) {
    $tr = array(
        "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
        "Д"=>"d","Е"=>"e","Ж"=>"zh","З"=>"z","И"=>"i",
        "Й"=>"i","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
        "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
        "У"=>"u","Ф"=>"f","Х"=>"kh","Ц"=>"c","Ч"=>"ch",
        "Ш"=>"sh","Щ"=>"shh","Ъ"=>"","Ы"=>"y","Ь"=>"",
        "Э"=>"e","Ю"=>"yu","Я"=>"ya",
		"а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
        "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
        "ы"=>"y","ь"=>"","э"=>"je","ю"=>"ju","я"=>"ja",
		" "=>"-"
		);
    return strtr($str,$tr);
	}
}
?>