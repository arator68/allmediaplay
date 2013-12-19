<?php
/**
 * @package RK-Softwareentwicklung AllMediaPlay Component
 * @author RK-Softwareentwicklung
 * @copyright (C) 2013 RK-Softwareentwicklung
 * @version 1.0.0
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_allmediaplay&task=allmediaplay.edit&id=' . $item->id); ?>">
				<?php echo $item->name; ?>
			</a>
		</td>
                <td>
			<a href="<?php echo JRoute::_('index.php?option=com_allmediaplay&task=allmediaplay.edit&id=' . $item->id); ?>">
				<?php echo $item->description; ?>
			</a>
		</td>
	</tr>
<?php endforeach; ?>

