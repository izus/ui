<?php

App::uses('AppHelper', 'View/Helper');

class UiHelper extends AppHelper {


/**
 * getBodyClasses
 * 
 * Devuelve las clases css para imprimir en el tag body, indicando action, controller y page actual.
 * Sample: <body class="section-pages action-display param-home">
 * 
 * @return string
 * @author il mostro
 */
	public function getBodyClasses() {
		$body_class = array();
		$body_class[] =	'section-' . h(strtolower($this->request->params['controller']));
		$body_class[] =	'action-'  . h(strtolower($this->request->params['action']));
		if(!empty($this->request->params['pass'][0])){
			if(strtolower($this->request->params['controller']) == 'pages'){	
				$body_class[] =	'page-' . h(strtolower($this->request->params['pass'][0]));
			}else{
				$body_class[] =	'param-' . h(strtolower($this->request->params['pass'][0]));
			}
		}
		return implode(' ', $body_class);
	}



	public function hasNamedParam($params = array()) {

		if(!is_array($params)) {
			$params = array($params);
		}

		foreach($params as $param) {
			if(isset($this->request->params['named'][$param]) && (!empty($this->request->params['named'][$param]) || @$this->request->params['named'][$param] === '0')) {
				return true;
			}
		}
		return false;
	}

	public function displayBoolean($value) {
		return  ($value) ? __d('ui','Yes') : __d('ui','No');
	}

	public function displayCount($value) {
		return (empty($value) || $value == 0) ? __d('ui','None') : $value;
	}

	public function displayImage($arr = array(), $k_filename = 'filename', $k_dir = '_dir', $opts = array() ) {
		// array('class'=>'thumbnail','style'=>'width:100%')
		return $this->Html->image(DS. $arr[$k_dir] . DS . $arr[$k_filename], $opts);
	}
	

	public function formatDate($date, $options = array()) {
	
		$_defoptions = array(
			'time' => false,
			'html' => true,
		);
		$opts = array_merge($_defoptions,$options);

		if(empty($date)) return '';
		
		$class = 'date';
		$ts = strtotime($date);
		
		$format = 'd-m-Y';
		if($opts['time']) {
			$format .= ' H:i';
			$class  .= 'time';
		}
		
		$formatted_date = date($format, $ts);
		
		if($opts['html']) {
			return sprintf('<span class="%s">%s</span>',$class,$formatted_date);
		}
		return $formatted_date;
	}

	// convenience wrapper
	public function formatDateTime($date, $options = array()) {
		$options['time'] = true;
		return $this->formatDate($date, $options);
	}
	
	
	public function format($value) {
		if($this->_is_date($value))
			return $this->formatDate($value);
		
		if(is_string($value))
			return h($value);
	
		if(is_bool($value))
			return $this->displayBoolean($value);
		
		if(is_numeric($value))
			return $this->displayCount($value);
	}
	
	public function _is_date($value) {
		
		return (strtotime($value) !== false);
		//
		$regex = '/([0-9]{2}|[0-9]{4})(-|\/)([0-9]{2})(-|\/)([0-9]{2}|[0-9]{4})( [0-9]{1,2}:[0-9]{1,2})?/';
		return preg_match($regex,$value);
	}

	public function formatDateInterval($from, $to = null, $force_date = false, $max_interval = 30) { // formatDateInterval In Days!
		
		// TODO: internationalize!
		
		$date_from = new DateTime($from);
		if(!$to)  {
			$date_to = new DateTime('now');
		} else {
			$date_to = new DateTime($to);
		}
		$diff = $date_to->diff($date_from);
		$days = $diff->format('%r%a');

		if($days >= $max_interval || $days <= -$max_interval || $force_date)
			return 'el ' . $this->formatDate($from);

		//echo $days;
		if($days == 0) {
			$sameday = $date_from->format('d') - $date_to->format('d');
			
			if((int)$sameday==0) {
				return (!$to) ? __d('ui','today')     : __d('ui','the same day');
			} elseif($sameday>0) {
				return (!$to) ? __d('ui','tomorrow')  : __d('ui','the next day');
			} else {
				return (!$to) ? __d('ui','yesterday') : __d('ui','the day before');
			}
		} elseif($days < 0) {
			if($days == -1)
				return __d('ui','yesterday');
			$text = 'hace';
			$class = 'ago';
		} else {
			$text = 'en';
			$class = 'to';
		}
		
		// TODO: internacionalizar este string! 10 days ago - 10 días atrás - hace 10 dias ? - en 10 días - in 10 days ?
		return sprintf("<span class=\"date-interval\"><span class=\"prefix\">%s</span> <span class=\"value\"><span class=\"days %s\">%d</span> días</span></span>",$text ,$class, abs($days));
	}

	/**
	 * formatCurrency
	 * 
	 * formatea un float como moneda, con separadores de miles y simbolo $, mostrar lo decimales es opcional
	 * 	
	 * @name formatCurrency
	 * @param float $amount
	 * @param array $options 
	 * @return string
	 * @access public
	 */
	public function formatCurrency($amount, $options = array()) {
		
		$_defaults = array(
			'currency'           => 'ARS',
			'symbol_left'        => '$',
			'symbol_right'       => '',
			'decimals'           => 2,
			'html'               => true,
			'force_decimals'     => false,
			'decimal_separator'  => ',',
			'thousand_separator' => '.',
			'absolute'           => false,
			'sign_before'        => '(', // '-'
			'sign_after'         => ')', // ''
		);
		$options = array_merge($_defaults, $options);

		//TODO: create a param 'empty' so it can be customized, add a default value: '' 
		if(empty($amount)){
			return '&nbsp;';
		}

		$sign_before = '';
		$sign_after  = '';

		if(!$options['absolute'] && ($amount < 0)) {
			$sign_before = $options['sign_before'];
			$sign_after  = $options['sign_after'];
		}
		$amount = abs($amount);
		$amount = number_format($amount,(int)$options['decimals'],$options['decimal_separator'],$options['thousand_separator']);
		$parts  = explode($options['decimal_separator'], $amount);
		$int_part = $parts[0];
		if(isset($parts[1])) {
			$dec_part = ($options['force_decimals']) ? $parts[1] : rtrim($parts[1],'0');
		} elseif($options['force_decimals']) {
			$dec_part = str_repeat('0', $options['decimals']);
		}
		if(!$options['html']) {
			$formatted  =  $sign_before;
			if(!empty($options['symbol_left'])) 
				$formatted .= $options['symbol_left'];
			$formatted .= $int_part;
			if($options['decimals'] && !empty($dec_part))
				$formatted .= $options['decimal_separator'].$dec_part;
			if(!empty($options['symbol_right'])) 
				$formatted .= $options['symbol_right'];
			$formatted .= $sign_after;
			return $formatted;
		}

		
		$formatted  = '<span class="currency">'.$sign_before;
		if(!empty($options['symbol_left']))  
			$formatted .=     '<span class="currency-symbol-'.strtolower($options['currency']).' currency-symbol currency-symbol-left">'.$options['symbol_left'].'</span>';
		$formatted .=     '<span class="amount">';
		$formatted .=         '<span class="integer">' . $int_part . '</span>';
		if($options['decimals'] && !empty($dec_part))
			$formatted .=     '<span class="comma">'.$options['decimal_separator'].'</span><span class="decimal">' . $dec_part . '</span>';
		$formatted .=     '</span>';
		if(!empty($options['symbol_right'])) 
			$formatted .= '<span class="currency-symbol-'.strtolower($options['currency']).' currency-symbol currency-symbol-right">' . $options['symbol_right'] . '</span>';
		$formatted .= $sign_after.'</span>';
		return $formatted;
	}
	
	/**
	 * formatQuantity
	 * 
	 * formatea la cantidad de un pedido eliminando los decimales si estan en cero
	 * 	 *
	 * @name formatQuantity
	 * @param float
	 * @return string
	 * @access public
	 */
	public function formatQuantity($quantity, $options = array()) {
		$_defaults = array(
		
		);
		$options = array_merge($_defaults, $options);
		if(! (intval($quantity) == ($quantity / 1)) ) {
			$convert = number_format($quantity,2,',','.');
			list(,$decimals) = split(',',(string)$convert);
			//$decimals = 
			//debug($parts);
		}
		$formatted  = '<span class="quantity">'; 
		$formatted .=     '<span class="integer">'.intval($quantity).'</span>';
		if(isset($decimals)) $formatted .= '<span class="comma">,</span><span class="decimal">'. $decimals . '</span>';
		$formatted .= '</span>';
		return $formatted;
	}
	
	public function isPage() {
		$argc = func_num_args();
		if($argc == 0) return @$this->request->params['controller'] == 'pages';

		if($argc > 1) {
			$is = array_filter(func_get_args(),array(__CLASS__,__METHOD__));
			return !empty($is);
		} else {
			$link = func_get_arg(0);
			//var_dump(Router::url($link)); echo '<br>';
			//var_dump(Router::url($this->request->url)); echo '<hr>';
			//return Router::url($link) == $this->request->url;
			// /*
			if(!empty($link['plugin']) && $link['plugin'] != $this->request->params['plugin']) {
				return false;
			}
			unset($link['plugin']);
			if(!empty($link['controller']) && $link['controller'] != $this->request->params['controller']) {
				return false;
			}
			unset($link['controller']);
			if(!empty($link['action']) && $link['action'] != $this->request->params['action']) {
				return false;
			}
			unset($link['action']);
			if(!empty($link) && (empty($this->request->params['pass']) && empty($this->request->params['named']))) {
				return false;
			}
			foreach($link as $_param => $value) {
				// TODO: la lógica debería ser al revés: reviso this->params y compruebo si alguno no es igual
				$check = is_numeric($_param)?'pass':'named';
				if(!in_array($value, $this->request->params[$check])) {
					return false;
				}
			}
			return true;
			// */
		}
	}	


}

