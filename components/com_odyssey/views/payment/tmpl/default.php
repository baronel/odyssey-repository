<?php
/**
 * @package Odyssey
 * @copyright Copyright (c) 2016 - 2017 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

// no direct access
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/helpers/order.php';

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

//Check if the utility temporary data has already been created by the setPayment
//controller function. 
$session = JFactory::getSession();
$travel = $session->get('travel', array(), 'odyssey'); 
$settings = $session->get('settings', array(), 'odyssey'); 
$utility = OrderHelper::getTemporaryData($travel['order_id'], true);

//Get the current language tag.
$langTag = JFactory::getLanguage()->getTag();
//Remove possible spaces from the string then turn it into a table.
$articleIds = preg_replace('/\s+/', '', $settings['gts_article_ids']);
$articleIds = explode(';', $articleIds);
$articleId = 0;
//Parse the article id corresponding to the current language tag.
foreach($articleIds as $articleId) {
  if(preg_match('#^'.$langTag.'=([0-9]+)$#', $articleId, $matches)) {
    $articleId = $matches[1];
    break;
  }
}
?>
<script>
function checkGTS() {
  var checkbox = document.getElementById('agreeing-gts').checked;

  if(checkbox) {
    hideButton('btn');
    document.getElementById('payment-modes').submit();
  }
  else {
    alert('<?php echo JText::_('COM_ODYSSEY_GENERAL_TERMS_OF_SALE_NOT_CHECKED'); ?>');
    document.getElementById('general-terms-sale').style.backgroundColor = '#f78181';
    //Prevent to submit form. 
    document.getElementById('payment-modes').addEventListener('submit', function(event){
      event.preventDefault()
    });
  }
}
</script>

<div class="blog" id="odyssey-payment">


<?php if(!is_null($utility) && !empty($utility['plugin_output'])) : ?>

  <?php echo $utility['plugin_output']; //Display the html output generated by plugins.  ?>

<?php else : //Display the available payment modes. ?>

  <?php echo JLayoutHelper::render('booking_breadcrumb', array('position' => 'payment', 'travel' => $travel),
				    JPATH_SITE.'/components/com_odyssey/layouts/'); ?>

  <h1 class="main-title"><?php echo JText::_('COM_ODYSSEY_CHOOSE_PAYMENT_MODE_TITLE'); ?></h1>

  <?php $paymentPlugins = TravelHelper::getPaymentModes();//Get the available payment plugins.?>

  <form action="<?php echo JRoute::_('index.php?option=com_odyssey&task=payment.setPayment');?>"
	method="post" id="payment-modes">
  <?php
   foreach($paymentPlugins as $id => $paymentPlugin) :
     $checked = '';
     if(!$id) { //Check by default the first entry found.
       $checked = 'checked="checked"';
     } ?>

     <div class="group">
      <h2><?php echo $paymentPlugin->name; ?></h2>
     <input type="radio" class="payment-rb" name="payment" value="<?php echo $paymentPlugin->plugin_element; ?>" <?php echo $checked; ?> />
       <?php echo $paymentPlugin->description; ?>
     </div>

  <?php endforeach; ?>

  <div id="general-terms-sale">
    <input id="agreeing-gts" type="checkbox" />
    <p><?php echo JText::_('COM_ODYSSEY_GENERAL_TERMS_OF_SALE_TEXT'); ?></p>
    <p><a href="<?php echo JRoute::_('index.php?option=com_content&view=article&tmpl=component&id='.(int)$articleId);?>" target="_blank"><?php echo JText::_('COM_ODYSSEY_READ_GENERAL_TERMS_OF_SALE'); ?></a></p>
  </div>

  <div id="btn-message">
    <input id="submit-button" class="btn" type="submit" onclick="checkGTS();" value="<?php echo JText::_('COM_ODYSSEY_GO'); ?>" />
  </div>

  </form>
<?php endif; ?>

</div>


