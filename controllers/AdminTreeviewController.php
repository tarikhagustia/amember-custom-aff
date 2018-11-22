<?php
/*
*
*
*     Author: Alex Scott
*      Email: alex@cgi-central.net
*        Web: http://www.cgi-central.net
*    Details: Admin Info / PHP
*    FileName $RCSfile$
*    Release: 5.5.4 ($Revision$)
*
* Please direct bug reports,suggestions or feedback to the cgi-central forums.
* http://www.cgi-central.net/forum/
*
* aMember PRO is a commercial software. Any distribution is strictly prohibited.
*
*/

class Aff_AdminTreeviewController extends Am_Mvc_Controller
{

    protected $tree = "";
    public function checkAdminPermissions(Admin $admin)
    {
        return $admin->isSuper();
    }

    function indexAction()
    {
        // Judul Halaman
        $this->view->title = ___('Treeview');

        function render($upline) {
            $downlines = Am_Di::getInstance()->db->select("SELECT * FROM ?_user WHERE aff_id = {$upline['user_id']} ORDER BY LOGIN ASC");
            $html = "";
            if (count($downlines) > 0) {
              $html .= sprintf('<li>
                  <input type="checkbox" class="expander">
                  <span class="expander"></span>
                  <label>%s - %s</label>', $upline['login'], $upline['name_f'] . ' ' . $upline['name_l']);
              $html .= "<ul>";
              foreach ($downlines as $key => $downline) {
                $html .= render($downline);
              }
              $html .= "</ul>";
              $html .= "</li>";
            }else{
              $html = sprintf('<li>
                  <input type="checkbox" class="expander" disabled>
                  <span class="expander"></span>
                  <label>%s - %s</label>
              </li>', $upline['login'], $upline['name_f'] . ' ' . $upline['name_l']);
            }

            return $html;

        }
        $html = "";
        $uplines = $this->getDi()->db->select("SELECT * FROM ?_user WHERE aff_id IS NULL");
        foreach ($uplines as $key => $upline) {
          $html .= render($upline);
        }

        // echo "<pre>";
        // var_dump($html); die();
        // echo "</pre>";
        // Get root account list
        $content = '<div class="grid-container">
        <style>
        .grid-container {
          padding : 5px;
        }
        .css-treeview
        {
            list-style: none;

        }
        .css-treeview li
        {
            padding-left : 10px !important;

        }


        /* Align the label and provide a pointer cursor. */
        .css-treeview label
        {
            display: inline;
            vertical-align: middle;
            cursor: pointer;
        }

        /* Highlight selected nodes. */
        .css-treeview label.selected
        {
            background-color: #08C;
            color: white;
            padding: 2px;
        }

        /* Hide child nodes of an unchecked expander. */
        .css-treeview input.expander ~ ul
        {
            display: none;
        }

        /* Show child nodes of a checked expander. */
        .css-treeview input.expander:checked ~ ul
        {
            display: block;
        }

        /* Hide the expander checkbox. */
        .css-treeview input.expander
        {
            position: absolute;
            opacity: 0;
        }


        .css-treeview input.expander:disabled
        {
            cursor: default;
        }

        /* Remove the margin from actual checkboxes. */
        .css-treeview input.check
        {
            margin: 0;
        }


        .css-treeview input.expander:disabled + span.expander::before
        {
            content: "";
            padding-right: 20px;
        }


        .css-treeview input.expander:enabled + span.expander::before {
            background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAgCAYAAAAbifjMAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAadEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41LjEwMPRyoQAAAHtJREFUSEvtlEsKwCAMRHP/S6fYUhDsfETSQnEhrvIcM5NEZsbKWSpuD9cB4mTr70EFDeBAJEBBLACD2AAEeQ+AHLEUMDslQGWBAlRxbZSd17eCa9TrFso3LtxbSN29uqEHM8WwiQjy1Bc5jWq5UhtV8R9z4KaP5mAWcgD5xILE2Y3q1wAAAABJRU5ErkJggg==");
            background-position: 0 0;
            content: "";
            padding-right: 20px;
        }


        .css-treeview input.expander:checked:enabled + span.expander::before
        {
            background-position: 1px 16px;
        }
        </style>
        <ul class="css-treeview">
          <li>
              <!--The expander checkbox. This will be hidden by having its opacity set to 0. The enabled attribute
              controls whether the expander will be shown, set it to true if there are child elements. The checked
              attribute controls whether the child items are expanded. -->
              <input type="checkbox" class="expander" checked>

              <!-- The expander image will be added to this span and placed over the expander checkbox using the
              ::before css rule. -->
              <span class="expander"></span>

              <!-- Here is the checkbox. This works as normal. -->


              <!-- The tree nodes label. -->
              <label>Root Node</label>
              <ul class="css-treeview">
                '.$html.'
              </ul>
              <!-- The child nodes. -->

          </li>
      </ul>
            </div>';
        $this->view->assign('content', $content);
        $this->view->display("admin/layout.phtml");
    }

    public function renderStatic(){

    }
}
