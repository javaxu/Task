<?php
/**
 * The set view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php 
$itemRow = <<<EOT
  <tr class='text-center'>
    <td>
      <input type='text' class="form-control"  value="" name="keys[]">
      <input type='hidden' value="0" name="systems[]">
    </td>
    <td>
      <input type='text' class="form-control" value="" name="values[]">
    </td>
    <td class='text-left'>
      <a href='javascript:void()' class='btn-icon' onclick='addItem(this)'><i class='icon-plus'></i></a>
      <a href='javascript:void()' class='btn-icon' onclick='delItem(this)'><i class='icon-remove'></i></a>
    </td>
  </tr>
EOT;
?>
<?php js::set('itemRow', $itemRow)?>
<?php js::set('module',  $module)?>
<?php js::set('field',   $field)?>
<div id='featurebar'>
  <ul class='nav'>
  <?php
  foreach($lang->custom->object as $object => $name)
  {
      echo "<li id='{$object}Tab'>"; 
      common::printLink('custom', 'set', "module=$object",  $name); 
      echo '</li>';
  }
  echo '<li>'; 
  common::printLink('custom', 'flow', "",  $lang->custom->flow); 
  echo '</li>';
  ?>
  </ul>
</div>
<div class='side'>
  <div class='list-group'>
    <?php 
    foreach($lang->custom->{$module}->fields as $key => $value)
    {
        echo "<li class='list-group-item' id='{$key}Tab'>" . html::a(inlink('set', "module=$module&field=$key"), $value) . "</li>";
    }
    ?>
  </div>
</div>
<div class='main'>
  <form method='post' class='form-condensed' target='hiddenwin'>
    <div class='panel panel-sm'>
      <div class='panel-heading'>
        <strong><?php echo $lang->custom->object[$module] . $lang->arrow . $lang->custom->$module->fields[$field]?></strong>
      </div>
      <?php if($module == 'story' and $field == 'review'):?>
      <table class='table table-form mw-800px'>
        <tr>
          <th class='w-80px'><?php echo $lang->custom->storyReview;?></th>
          <td><?php echo html::radio('needReview', $lang->custom->reviewList, $needReview);?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->custom->forceReview;?></th>
          <td><?php echo html::select('forceReview[]', $users, $forceReview, "class='form-control chosen' multiple");?></td>
          <td class='w-180px'><?php echo $lang->custom->notice->forceReview;?></td>
        </tr>
        <tr>
          <td></td>
          <td><?php echo html::submitButton();?></td>
        </tr>
      </table>
      <?php elseif($module == 'task' and $field == 'hours'):?>
      <table class='table table-form mw-600px'>
        <tr>
          <th class='w-150px'><?php echo $lang->custom->workingHours;?></th>
          <td><?php echo html::input('defaultWorkhours', $workhours, "class='form-control w-80px'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->custom->weekend;?></th>
          <td><?php echo html::radio('weekend', $lang->custom->weekendList, $weekend);?></td>
        </tr>
        <tr>
          <td></td>
          <td><?php echo html::submitButton();?></td>
        </tr>
      </table>
      <?php elseif($module == 'bug' and $field == 'longlife'):?>
      <table class='table table-form mw-600px'>
        <tr>
          <th class='w-100px'><?php echo $lang->custom->bug->fields['longlife'];?></th>
          <td class='w-100px'>
            <div class='input-group'>
              <?php echo html::input('longlife', $longlife, "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->day?></span>
            </div>
          </td>
          <td><?php echo html::submitButton();?></td>
        </tr>
      </table>
      <div class='alert alert-info alert-block'><?php echo $lang->custom->notice->longlife;?></div>
      <?php else:?>
      <table class='table table-form active-disabled table-condensed mw-600px'>
        <tr class='text-center'>
          <td class='w-120px'><strong><?php echo $lang->custom->key;?></strong></td>
          <td><strong><?php echo $lang->custom->value;?></strong></td>
          <?php if($canAdd):?><th class='w-40px'></th><?php endif;?>
        </tr>
        <?php foreach($fieldList as $key => $value):?>
        <tr class='text-center'>
          <?php $system = isset($dbFields[$key]) ? $dbFields[$key]->system : 1;?>
          <td><?php echo $key === '' ? 'NULL' : $key; echo html::hidden('keys[]', $key) . html::hidden('systems[]', $system);?></td>
          <td>
            <?php echo html::input("values[]", isset($dbFields[$key]) ? $dbFields[$key]->value : $value, "class='form-control'");?>
          </td>
          <?php if($canAdd):?>
          <td class='text-left w-100px'>
            <a href='javascript:void()' class='btn-icon' onclick='addItem(this)'><i class='icon-plus'></i></a>
            <a href='javascript:void()' onclick='delItem(this)' class='btn-icon'><i class='icon-remove'></i></a>
          </td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
        <tr>
          <td colspan='<?php $canAdd ? print(3) : print(2);?>' class='text-center'>
          <?php 
          $appliedTo = array($currentLang => $lang->custom->currentLang, 'all' => $lang->custom->allLang);
          echo html::radio('lang', $appliedTo, $lang2Set);
          echo html::submitButton();
          if(common::hasPriv('custom', 'restore')) echo html::linkButton($lang->custom->restore, inlink('restore', "module=$module&field=$field"), 'hiddenwin');
          ?>
          </td>
        </tr>
      </table>
      <?php if(!$canAdd):?>
      <div class='alert alert-warning alert-block'><?php echo $lang->custom->notice->canNotAdd;?></div>
      <?php endif;?>
      <?php endif;?>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
