<?php
/**
 * @package Odyssey
 * @copyright Copyright (c) 2016 - 2017 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/helpers/travel.php';


/**
 * @package     Joomla.Site
 * @subpackage  com_odyssey
 */
class OdysseyControllerPassengers extends JControllerForm
{
  public function checkUser()
  {
    TravelHelper::checkBookingProcess();

    $user = JFactory::getUser();

    //If SEF is enabled we must set the Itemid variable to zero in order to
    //avoid SEF to bind any previous menu item id to the address or registration view.  
    $Itemid = '';
    if(JFactory::getConfig()->get('config.sef', false)) {
      $Itemid = '&Itemid=0';
    }

    //Grab the user session.
    $session = JFactory::getSession();
    $session->set('location', 'passengers', 'odyssey'); 
    $travel = $session->get('travel', array(), 'odyssey'); 

    //If the user is logged we redirect him to the first ordering step.
    if($user->id > 1) {
      $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view=passengers'.$Itemid.'&alias='.$travel['alias'], false));
    }
    else { //The user must login or registrate.
      //Note: Passes a booking flag to allow the login template to detect where the user is coming from. 
      $this->setRedirect(JRoute::_('index.php?option=com_users&view=login&booking=1'.$Itemid, false));
    }

    return;
  }
}

