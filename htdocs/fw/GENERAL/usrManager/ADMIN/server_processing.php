<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

$aColumns     = array();
$sIndexColumn = '';
$sTable       = '';


function classesVars(&$aColumns=array(), &$sIndexColumn='', &$sTable='') {
    $aColumns     = array( 'cid', 'name', 'groups');
    $sIndexColumn = "cid";
    $sTable       = "auth_classes_datatables";
}

function groupsVars(&$aColumns=array(), &$sIndexColumn='', &$sTable='') {
    $aColumns     = array( 'gid', 'name', 'description');
    $sIndexColumn = "gid";
    $sTable       = "auth_groups";
}

function usersVars(&$aColumns=array(), &$sIndexColumn='', &$sTable='') {
    $aColumns     = array( 'uid', 'name', 'uclass', 'full_name', 'email', 'active' );
    $sIndexColumn = "uid";
    $sTable       = "auth_users_datatables";
}

if(isset($_GET)) {
    $_GET['fn']($aColumns,$sIndexColumn,$sTable);
}


/* Database connection information */

$gaSql['user']       = "root";
$gaSql['password']   = "hibernia@!";
$gaSql['db']         = "blacksea_dev";
$gaSql['server']     = "localhost";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
 * no need to edit below this line
 */

/*
 * Local functions
 */
function fatal_error ( $sErrorMessage = '' )
{
    header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
    die( $sErrorMessage );
}


/*
 * MySQL connection
 */
if ( ! $gaSql['link'] = mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) )
{
    fatal_error( 'Could not open connection to server' );
}

if ( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
{
    fatal_error( 'Could not select database ' );
}

mysql_set_charset ("utf8");

/*
 * Paging
 */
$sLimit = "";
if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
{
    $sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
        intval( $_GET['iDisplayLength'] );
}


/*
 * Ordering
 */
$sOrder = "";
if ( isset( $_GET['iSortCol_0'] ) )
{
    $sOrder = "ORDER BY  ";
    for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
    {
        if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
        {
            $sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
                ($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
        }
    }

    $sOrder = substr_replace( $sOrder, "", -2 );
    if ( $sOrder == "ORDER BY" )
    {
        $sOrder = "";
    }
}


/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
$sWhere = "";
if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
{
    $sWhere = "WHERE (";
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
        {
            $sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
        }
    }
    $sWhere = substr_replace( $sWhere, "", -3 );
    $sWhere .= ')';
}

/* Individual column filtering */
for ( $i=0 ; $i<count($aColumns) ; $i++ )
{
    if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
    {
        if ( $sWhere == "" )
        {
            $sWhere = "WHERE ";
        }
        else
        {
            $sWhere .= " AND ";
        }
        $sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
    }
}


/*
 * SQL queries
 * Get data to display
 */
$sQuery = "
    SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
    FROM   $sTable
    $sWhere
    $sOrder
    $sLimit
    ";
$rResult = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );

/* Data set length after filtering */
$sQuery = "
    SELECT FOUND_ROWS()
";
$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

/* Total data set length */
$sQuery = "
    SELECT COUNT(`".$sIndexColumn."`)
    FROM   $sTable
";
$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
$aResultTotal = mysql_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];


/*
 * Output
 */
$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);

while ( $aRow = mysql_fetch_array( $rResult ) )
{
    $row = array();

	// Add the row ID and class to the object
	$row['DT_RowId'] = $aRow[$sIndexColumn];

    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ( $aColumns[$i] == "version" )
        {
            /* Special output formatting for 'version' column */
            $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
        }
        else if ( $aColumns[$i] != ' ' )
        {
            /* General output */
            $row[] = $aRow[ $aColumns[$i] ];
        }
    }
    $output['aaData'][] = $row;
}

//file_put_contents('json.txt',json_encode( $output ));
echo json_encode( $output );
?>
