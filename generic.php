<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// user information to determine the school..
$user = JFactory::getUser()->username;
$path = Joomla\CMS\Uri\Uri::getInstance()->toString();
$levels = JAccess::getAuthorisedViewLevels(JFactory::getUser()->id);
if ($user == null || !in_array('75', $levels)) {
    // Preschool access level
    JFactory::getApplication()->enqueueMessage(JText::_("Must be Sevier School District Employee"), 'error');
    JFactory::getApplication()->redirect('/staff');
}

//JSession::checkToken() or die( 'Invalid Token for preschool' );

$token = JSession::getFormToken() . '=1';

require_once JPATH_ROOT . '/php/d3json.php';

// Update the breadcrumbs
$app = JFactory::getApplication();
$pathway = $app->getPathway();
$pathforme = array();
$pathforme[0] = new stdClass();
$pathforme[0]->name = "Employee Portal";
$pathforme[0]->link = "/index.php/default-landing-page.html";
$pathforme[1] = new stdClass();
$pathforme[1]->name = "Site Specialist Main Menu ";
$pathforme[1]->link = "/index.php/site-specialist.html";
$pathforme[2] = new stdClass();
$pathforme[2]->name = "AB Calendar ";
$pathforme[2]->link = "/index.php/site-specialist.html";
$pathway->setPathway($pathforme);

$uri = Joomla\CMS\Uri\Uri::getInstance();
$path = $uri->toString();
include(JPATH_ROOT . '/components/com_seviersd/assets/default_vue3_include.php');

/*// make a db call....
$d3db = new d3json();
$d3db->setd3account($d3account);
$d3db->setd3app('ssd.master');
$d3db->setd3cmd('getabcalendar');
$d3db->setd3rpt('sitespecialist');
$jinput = JFactory::getApplication()->input;
$month = $jinput->get('month', '', '');
$year = $jinput->get('year', '', '');
$d3db->setpfield('month', $month);
$d3db->setpfield('year', $year);
$d3db->d3curl();
$results = $d3db->response;
$d3db->geterror();*/


?>
<input type="text" hidden value="<?php print $token; ?>" id="mytoken">
<div class="container mx-0 px-0" id="abcalendar">
    <div class="row" v-if="showMessageFlag">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">{{showMessage}}
            <button @click="showMessageFlag = false; showMessage = ''" type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <div class="row">
        <div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
            <div class="btn-group" role="group" aria-label="First group">
                <button class="btn btn-primary" type="button" @click="saveAB">Save</button>
            </div>
            <div class="input-group ms-4">
                <div class="input-group-text" id="btnGroupAddon">Month</div>
                <select class="form-select" v-model="selectedMonth" @change="getab">
                    <option v-for="item in month" :value="item.id">{{item.name}}</option>
                </select>
                <div class="input-group-text ms-4" id="btnGroupAddon">Year</div>
                <select class="form-select" v-for="years in results.yearlist" v-model="selectedYear" @change="getab">
                    <option :value="years.lastyear" v-model="selectedYear">{{results.lastyear}}</option>
                    <option :value="years.thisyear" v-model="selectedYear">{{results.year}}</option>
                    <option :value="years.nextyear" v-model="selectedYear">{{results.nextyear}}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" id="adays">A Days</span>
            <input type="text" style="background-color: white" :value="results.adays" readonly class="form-control">
            <span class="input-group-text ms-4" id="bdays">B Days</span>
            <input type="text" style="background-color: white" :value="results.bdays" readonly class="form-control">
            <span class="input-group-text ms-4" id="nodays">No School</span>
            <input type="text" style="background-color: white" :value="results.nodays" readonly class="form-control">
        </div>
        <div class="row">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Day</th>
                    <th scope="col">A/B</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in results.mymonth" :key="index">
                    <td>{{ item.sdate}}</td>
                    <td>{{ item.sday }}</td>
                    <td><select class="form-select" v-model="item.stype">
                            <option value="" v-model="item.stype">No School</option>
                            <option value="A" v-model="item.stype">A</option>
                            <option value="B" v-model="item.stype">B</option>
                            <option value="AB" v-model="item.stype">AB</option>
                        </select></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include(JPATH_ROOT . '/components/com_seviersd/views/sitespecialist/assets/abcalendar.vue'); ?>

