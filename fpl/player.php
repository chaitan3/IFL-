<?php 
if(!isset($con))
    include '../common/connect.php';
?>
    <tr>
        <th id="tl">Team</th><th class="left">Player</th><th class="left">Position</th><th>Value</th><th>Points</th><th id="tr">&nbsp;</th>
    </tr>
    <?php
        $str='';
        if(isset($_REQUEST['team']) && ($_REQUEST['team']!='any'))
        {
            $str=' and team="'.$_REQUEST['team'].'"';
        }
        if(isset($_REQUEST['pos']) && ($_REQUEST['pos']!='any'))
        {
            $str=$str.' and position="'.$_REQUEST['pos'].'"';
        }
        $offset=0;
        if(isset($_REQUEST['offset']))
            $offset=$_REQUEST['offset']*10;
        if(isset($_REQUEST['range']))
        {
            switch($_REQUEST['range'])
            {
                case 1:
                    $str=$str.' and bidding_value<25';
                    break;
                case 2:
                    $str=$str.' and bidding_value between 24 and 50';
                    break;
                case 3:
                    $str=$str.' and bidding_value between 49 and 100';
                    break;
                case 4:
                    $str=$str.' and bidding_value between 99 and 150';
                    break;
                case 5:
                    $str=$str.' and bidding_value between 149 and 250';
                    break;
                case 6:
                    $str=$str.' and bidding_value>=250';
                    break;
            }
        }
        $res=mysql_query('select id from ifl_player where 1=1 '.$str);
        $num_players=mysql_num_rows($res);
        $res=mysql_query('select * from ifl_player where 1=1 '.$str.' order by bidding_value desc LIMIT '.$offset.',10');
        
        $c=1;
        while($row=mysql_fetch_array($res))
        {
            if($c==1)
            {
                $t='class="one"';
                $c=0;
            }
            else
            {
                $t='class="two"';
                $c=1;
            }
            
            echo '<tr '.$t.'><td><img id="logo" src="../images/teams/'
            .$row['team'].'.png"/></td><td class="left">'.$row['name'].
            '</td><td class="left">'.$row['position'].'</td><td>'.$row['bidding_value'].'</td><td>'.$row['points'].'</td>
            <td><button onclick="add_player('.$row['id'].',\''.$row['name'].'\',\''.$row['position'].'\','.$row['bidding_value'].',\''.$row['team'].'\')">Add</button></td>
            </tr>';
        }
    echo '<tr class="one"><td id="bl">';
    if($offset>0)
        echo '<button onclick="player_query_prev()">Prev</button>';
    echo '</td><td colspan="4">&nbsp;</td><td id="br">';
    if($num_players-$offset>10)
        echo '<button onclick="player_query_next()">Next</button>';
    echo '</td></tr>';
    ?>
    

