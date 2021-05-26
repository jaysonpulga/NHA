<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*
Route::get('/', function () {
    return view('welcome');
});
*/


Route::get('/clear_cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
	$exitCode = Artisan::call('route:clear');
	
    return 'DONE'; //Return anything
});



Auth::routes();

Route::get('/', ['middleware' => 'auth', function (){ 
	return redirect()->action('HomeController@index'); 
}]);

Route::get('/dashboard', 'HomeController@index')->name('home');
Route::post('importExcel', 'ImportController@importExcel')->name('importExcel');
Route::get('/importdata', 'ImportController@importdata')->name('importdata');

Route::post('update_information', 'DatabaseController@update_information')->name('update_information');


Route::get('/database/voters', 'DatabaseController@voters')->name('voters');
Route::post('getVotersList', 'DatabaseController@getVotersList')->name('getVotersList');

/*
Route::get('/database/city', 'DatabaseController@city')->name('city');
Route::get('/database/barangay', 'DatabaseController@barangay')->name('barangay');
Route::get('/database/precint', 'DatabaseController@precint')->name('precint');
*/

/*
Route::post('getCitiesList', 'DatabaseController@getCitiesList')->name('getCitiesList');
Route::post('getBarangayList', 'DatabaseController@getBarangayList')->name('getBarangayList');
Route::post('getPrecintList', 'DatabaseController@getPrecintList')->name('getPrecintList');
Route::post('updatecluster', 'DatabaseController@updatecluster')->name('updatecluster');
*/


##################### ROLE ASSIGNMENT ROUTE #########################################################
Route::get('/RoleAssignment/Overview', 'RoleAssignmentController@Overview')->name('Overview');
Route::get('/RoleAssignment/Coordinator', 'RoleAssignmentController@Coordinator')->name('Coordinator');
Route::get('/RoleAssignment/Sub-coordinator', 'RoleAssignmentController@Subcoordinator')->name('Subcoordinator');
Route::get('/RoleAssignment/PurokLeader', 'RoleAssignmentController@PurokLeader')->name('PurokLeader');


##################### COORDINATOR ROUTE #########################################################
Route::post('getCoordinatorList', 'CoordinatorController@getCoordinatorList')->name('getCoordinatorList');
Route::post('getUserbelongtoMunicipality', 'CoordinatorController@getUserbelongtoMunicipality')->name('getUserbelongtoMunicipality');
Route::post('create_assign_coordinator', 'CoordinatorController@create_assign_coordinator')->name('create_assign_coordinator');

##################### SUB COORDINATOR ROUTE #########################################################
Route::post('getSubCoordinatorList', 'SubCoordinatorController@getSubCoordinatorList')->name('getSubCoordinatorList');
Route::post('getbarangaybelongtoMunicipality', 'SubCoordinatorController@getbarangaybelongtoMunicipality')->name('getbarangaybelongtoMunicipality');
Route::post('getUserbelongtobarangay', 'SubCoordinatorController@getUserbelongtobarangay')->name('getUserbelongtobarangay');
Route::post('create_assign_sub_coordinator', 'SubCoordinatorController@create_assign_sub_coordinator')->name('create_assign_sub_coordinator');


##################### PUROK LEADER ROUTE #########################################################
Route::post('getPurokLeaderList', 'PurokLeaderController@getPurokLeaderList')->name('getPurokLeaderList');
Route::post('getSubcoandbarangaybelongtoMunicipality', 'PurokLeaderController@getSubcoandbarangaybelongtoMunicipality')->name('getSubcoandbarangaybelongtoMunicipality');
Route::post('getUserAssignPurokLeadertobarangay', 'PurokLeaderController@getUserAssignPurokLeadertobarangay')->name('getUserAssignPurokLeadertobarangay');
Route::post('create_assign_purok_leader', 'PurokLeaderController@create_assign_purok_leader')->name('create_assign_purok_leader');

##################### Overview ROUTE #########################################################
Route::post('getPurokleaderbelongstobarangayNotyetSelected', 'OverviewCampaignGroupsController@getPurokleaderbelongstobarangayNotyetSelected')->name('getPurokleaderbelongstobarangayNotyetSelected');
Route::post('create_member', 'OverviewCampaignGroupsController@create_member')->name('create_member');
Route::post('getUserAssignAsMember', 'OverviewCampaignGroupsController@getUserAssignAsMember')->name('getUserAssignAsMember');
Route::post('getCampaignGroupList', 'OverviewCampaignGroupsController@getCampaignGroupList')->name('getCampaignGroupList');
Route::post('getMemberlistAndDetails', 'OverviewCampaignGroupsController@getMemberlistAndDetails')->name('getMemberlistAndDetails');


##################### CAMPAIGN PERIODS ROUTE #########################################################
Route::get('/campaign-periods', 'CampaignPeriodsController@campaignperiods')->name('campaignperiods');
Route::post('create_campaign_period', 'CampaignPeriodsController@create_campaign_period')->name('create_campaign_period');
Route::post('getMemberlistByGroupId', 'CampaignPeriodsController@getMemberlistByGroupId')->name('getMemberlistByGroupId');
Route::post('getPurokleaderbelongstobarangay', 'CampaignPeriodsController@getPurokleaderbelongstobarangay')->name('getPurokleaderbelongstobarangay');
Route::post('getGroupId', 'CampaignPeriodsController@getGroupId')->name('getGroupId');
Route::post('campaign_tally_periods', 'CampaignPeriodsController@campaign_tally_periods')->name('campaign_tally_periods');




##################### RELIGION ROUTE #########################################################
Route::get('/personalInfo/religion', 'ReligionController@religion')->name('religion');
Route::post('loadAllReligions', 'ReligionController@loadAllReligions')->name('loadAllReligions');
Route::post('saving_religion_info', 'ReligionController@saving_religion_info')->name('saving_religion_info');
Route::post('get_religion', 'ReligionController@get_religion')->name('get_religion');
Route::post('deletereligion', 'ReligionController@deletereligion')->name('deletereligion');


##################### PROVINCE ROUTE #########################################################
Route::get('/locationInfo/province', 'ProvinceLocationController@province')->name('province');
Route::post('saving_province_info', 'ProvinceLocationController@saving_province_info')->name('saving_province_info');
Route::post('loadAllprovince', 'ProvinceLocationController@loadAllprovince')->name('loadAllprovince');
Route::post('get_province', 'ProvinceLocationController@get_province')->name('get_province');
Route::post('deleteprovince', 'ProvinceLocationController@delete_province')->name('delete_province');


##################### CITIES ROUTE #########################################################
Route::get('/locationInfo/city', 'CitiesLocationController@city')->name('city');
Route::post('loadAllCities', 'CitiesLocationController@loadAllCities')->name('loadAllCities');
Route::post('saving_city_info', 'CitiesLocationController@saving_city_info')->name('saving_city_info');
Route::post('get_city', 'CitiesLocationController@get_city')->name('get_city');
Route::post('deletecity', 'CitiesLocationController@deletecity')->name('deletecity');

##################### BARANGAY ROUTE #########################################################
Route::get('/locationInfo/barangay', 'BarangayLocationController@barangay')->name('barangay');
Route::post('loadAllbarangay', 'BarangayLocationController@loadAllbarangay')->name('loadAllbarangay');
Route::post('saving_barangay_info', 'BarangayLocationController@saving_barangay_info')->name('saving_barangay_info');
Route::post('get_barangay', 'BarangayLocationController@get_barangay')->name('get_barangay');
Route::post('deletebarangay', 'BarangayLocationController@deletebarangay')->name('deletebarangay');


##################### PRECINCTS ROUTE #########################################################
Route::get('/locationInfo/precinct', 'PrecinctLocationController@precinct')->name('precinct');
Route::post('loadAllprecinct', 'PrecinctLocationController@loadAllprecinct')->name('loadAllprecinct');
Route::post('saving_precinct_info', 'PrecinctLocationController@saving_precinct_info')->name('saving_precinct_info');
Route::post('get_precinct', 'PrecinctLocationController@get_precinct')->name('get_precinct');
Route::post('deleteprecinct', 'PrecinctLocationController@deleteprecinct')->name('deleteprecinct');
Route::post('getbarangaybelongtoCity', 'PrecinctLocationController@getbarangaybelongtoCity')->name('getbarangaybelongtoCity');



##################### CAMPAIGN GROUP ROUTE #########################################################

##################### COORDINATOR  #########################################################
Route::get('/campaign-groups/coordinator', 'CampaignGroup_CoordinatorController@coordinator')->name('coordinator');
Route::post('searchAvailableCoordinator', 'CampaignGroup_CoordinatorController@searchAvailableCoordinator')->name('searchAvailableCoordinator');
Route::post('AssignCoordinator', 'CampaignGroup_CoordinatorController@AssignCoordinator')->name('AssignCoordinator');
Route::post('LoadCoordinator', 'CampaignGroup_CoordinatorController@LoadCoordinator')->name('LoadCoordinator');
Route::post('DeleteCoordinator', 'CampaignGroup_CoordinatorController@DeleteCoordinator')->name('DeleteCoordinator');
Route::post('printcoordinator', 'CampaignGroup_CoordinatorController@printcoordinator')->name('printcoordinator');
Route::post('exportcoordinator', 'CampaignGroup_CoordinatorController@exportcoordinator')->name('exportcoordinator');



##################### Leader  #########################################################
Route::get('/campaign-groups/leader', 'CampaignGroup_LeaderController@leader')->name('leader');
Route::post('getCoordinatorbelongtocityandbarangay', 'CampaignGroup_LeaderController@getCoordinatorbelongtocityandbarangay')->name('getCoordinatorbelongtocityandbarangay');
Route::post('searchAvailableLeader', 'CampaignGroup_LeaderController@searchAvailableLeader')->name('searchAvailableLeader');
Route::post('AssignLeader', 'CampaignGroup_LeaderController@AssignLeader')->name('AssignLeader');
Route::post('LoadLeader', 'CampaignGroup_LeaderController@LoadLeader')->name('LoadLeader');
Route::post('DeleteLeader', 'CampaignGroup_LeaderController@DeleteLeader')->name('DeleteLeader');
Route::post('printleader', 'CampaignGroup_LeaderController@printleader')->name('printleader');
Route::post('exportleader', 'CampaignGroup_LeaderController@exportleader')->name('exportleader');



##################### Member  #########################################################
Route::get('/campaign-groups/member', 'CampaignGroup_MemberController@member')->name('member');
Route::post('getLeaderbelongtoCoordinator', 'CampaignGroup_MemberController@getLeaderbelongtoCoordinator')->name('getLeaderbelongtoCoordinator');
Route::post('searchAvailableMembers', 'CampaignGroup_MemberController@searchAvailableMembers')->name('searchAvailableMembers');
Route::post('AssignMembers', 'CampaignGroup_MemberController@AssignMembers')->name('AssignMembers');
Route::post('AssignMembersModify', 'CampaignGroup_MemberController@AssignMembersModify')->name('AssignMembersModify');

Route::post('loadAllCampaignGroup', 'CampaignGroup_MemberController@loadAllCampaignGroup')->name('loadAllCampaignGroup');
Route::post('getMemberList', 'CampaignGroup_MemberController@getMemberList')->name('getMemberList');
Route::post('deleteRow', 'CampaignGroup_MemberController@deleteRow')->name('deleteRow');
Route::post('DeleteMemberlist', 'CampaignGroup_MemberController@DeleteMemberlist')->name('DeleteMemberlist');

Route::post('printmember', 'CampaignGroup_MemberController@printmember')->name('printmember');
Route::post('exportmember', 'CampaignGroup_MemberController@exportmember')->name('exportmember');

Route::post('printmemberdetails', 'CampaignGroup_MemberController@printmemberdetails')->name('printmemberdetails');

##################### ELECTION TURNOUT  #########################################################
Route::get('/turnout', 'ElectionTurnOutController@turnout')->name('turnout');
Route::post('LoadTurnout', 'ElectionTurnOutController@LoadTurnout')->name('LoadTurnout');
Route::post('getAvailableBarangayforTurnOut', 'ElectionTurnOutController@getAvailableBarangayforTurnOut')->name('getAvailableBarangayforTurnOut');
Route::post('getClusterbelongtoBarangay', 'ElectionTurnOutController@getClusterbelongtoBarangay')->name('getClusterbelongtoBarangay');
Route::post('editTurnout', 'ElectionTurnOutController@editTurnout')->name('editTurnout');
Route::post('getClusterTotalVoters', 'ElectionTurnOutController@getClusterTotalVoters')->name('getClusterTotalVoters');
Route::post('save_turnout', 'ElectionTurnOutController@save_turnout')->name('save_turnout');
Route::post('update_turnout', 'ElectionTurnOutController@update_turnout')->name('update_turnout');
Route::post('delete_turnout', 'ElectionTurnOutController@delete_turnout')->name('delete_turnout');

Route::post('print_turnout', 'ElectionTurnOutController@print_turnout')->name('print_turnout');
Route::post('excel_turnout', 'ElectionTurnOutController@excel_turnout')->name('excel_turnout');



##################### USER MANAGEMENT  #########################################################
Route::get('/userManagement', 'UserManagementController@userManagement')->name('userManagement');
Route::post('loadAllUsers', 'UserManagementController@loadAllUsers')->name('loadAllUsers');
Route::post('saving_account', 'UserManagementController@saving_account')->name('saving_account');
Route::post('get_user', 'UserManagementController@get_user')->name('get_user');
Route::post('delete_user', 'UserManagementController@delete_user')->name('delete_user');



##################### USER ROLE  #########################################################
Route::get('/userRole', 'UserRoleController@userRole')->name('userRole');
Route::post('loadRoles', 'UserRoleController@loadRoles')->name('loadRoles');
Route::post('saving_role', 'UserRoleController@saving_role')->name('saving_role');
Route::post('get_role', 'UserRoleController@get_role')->name('get_role');
Route::post('delete_role', 'UserRoleController@delete_role')->name('delete_role');



##################### NOMINEE  #########################################################
Route::get('/nominee', 'NomineeController@nominee')->name('nominee');


##################### ELECTION REPORTS  #########################################################
Route::get('/reports', 'PreElectionReportsController@reports')->name('reports');

#################### REPORT ################################################
Route::post('voters_gender', 'Report_GenderController@voters_gender')->name('voters_gender');
Route::post('print_voters_gender', 'Report_GenderController@print_voters_gender')->name('print_voters_gender');
Route::post('excel_voters_gender', 'Report_GenderController@excel_voters_gender')->name('excel_voters_gender');

Route::post('voters_age', 'Report_AgeController@voters_age')->name('voters_age');
Route::post('print_voters_age', 'Report_AgeController@print_voters_age')->name('print_voters_age');
Route::post('excel_voters_age', 'Report_AgeController@excel_voters_age')->name('excel_voters_age');

Route::post('voters_religion', 'Report_ReligionController@voters_religion')->name('voters_religion');
Route::post('print_voters_religion', 'Report_ReligionController@print_voters_religion')->name('print_voters_religion');
Route::post('excel_voters_religion', 'Report_ReligionController@excel_voters_religion')->name('excel_voters_religion');

Route::post('projected_voters', 'Report_ProjectedVotersController@projected_voters')->name('projected_voters');
Route::post('print_projected_voters', 'Report_ProjectedVotersController@print_projected_voters')->name('print_projected_voters');
Route::post('excel_projected_voters', 'Report_ProjectedVotersController@excel_projected_voters')->name('excel_projected_voters');


Route::post('Barangay_Coordination_Report', 'Barangay_Coordination_ReportController@Barangay_Coordination_Report')->name('Barangay_Coordination_Report');
Route::post('print_Barangay_Coordination_Report', 'Barangay_Coordination_ReportController@print_Barangay_Coordination_Report')->name('print_Barangay_Coordination_Report');
Route::post('excel_Barangay_Coordination_Report', 'Barangay_Coordination_ReportController@excel_Barangay_Coordination_Report')->name('excel_Barangay_Coordination_Report');



