<?php
/**
 * @package Helix_Ultimate_Framework
 * @author JoomShaper <support@joomshaper.com>
 * @copyright Copyright (c) 2010 - 2018 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/**
 * Form field for Helix gallery.
 *
 * @since	1.0.0
 */
class JFormFieldHelixgallery extends FormField
{
	/**
	 * Field type
	 *
	 * @var		string	$type
	 * @since	1.0.0
	 */
	protected $type = 'Helixgallery';

	/**
	 * Override getInput function form FormField
	 *
	 * @return	string	Field HTML string
	 * @since	1.0.0
	 */
	protected function getInput()
	{
		$doc = Factory::getDocument();

		HTMLHelper::_('jquery.framework');
		HTMLHelper::_('jquery.ui', array('core', 'sortable'));

		$plg_path = Uri::root(true) . '/plugins/system/helixultimate';

		$values = json_decode($this->value);

		if (!empty($values))
		{
			$images = $this->element['name'] . '_images';
			$values = $values->$images;
		}
		else
		{
			$values = array();
		}

		$output  = '<div class="helix-ultimate-gallery-field">';
		$output .= '<ul class="helix-ultimate-gallery-items clearfix">';

		if (is_array($values) && !empty($values))
		{
			foreach ($values as $key => $value)
			{
				$data_src = $value;

				$src = Uri::root(true) . '/' . $value;

				$basename = basename($src);

				$thumbnail = JPATH_ROOT . '/' . dirname($value) . '/' . File::stripExt($basename) . '_thumbnail.' . File::getExt($basename);
				$small_size = JPATH_ROOT . '/' . dirname($value) . '/' . File::stripExt($basename) . '_small.' . File::getExt($basename);

				if (file_exists($thumbnail))
				{
					$src = Uri::root(true) . '/' . dirname($value) . '/' . File::stripExt($basename) . '_thumbnail.' . File::getExt($basename);
				}
				elseif (file_exists($small_size))
				{
					$src = Uri::root(true) . '/' . dirname($value) . '/' . File::stripExt($basename) . '_small.' . File::getExt($basename);
				}

				$output .= '<li class="helix-ultimate-gallery-item" data-src="' . $data_src . '"><a href="#" class="btn btn-mini btn-danger btn-helix-ultimate-remove-gallery-image"><span class="fas fa-times"></span></a><img src="' . $src . '" alt=""></li>';
			}
		}

		$output .= '</ul>';

		$output .= '<input type="file" id="helix-ultimate-gallery-item-upload" accept="image/*" multiple="multiple" style="display:none;">';
		$output .= '<a class="btn btn-default btn-secondary btn-helix-ultimate-gallery-item-upload" href="#"><i class="fas fa-plus"></i> ' . Text::_('HELIX_ULTIMATE_UPLOAD_IMAGES') . '</a>';

		$output .= '<input type="hidden" name="' . $this->name . '" data-name="' . $this->element['name'] . '_images" id="' . $this->id . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8')
				. '"  class="form-field-helix-ultimate-gallery">';
		$output .= '</div>';

		return $output;
	}
}
