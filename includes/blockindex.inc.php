<?php

$txrow = "";

$stmt = $mysqli->prepare("SELECT `height`, `hash`, `time`, `size`, `transactions` FROM `blocks` WHERE FROM_UNIXTIME(`time`, '%Y-%m-%d') LIKE ? ORDER BY `height` DESC");
$stmt->bind_param('s', $dateblocks);
$stmt->execute();

$stmt->bind_result($height, $hash, $time, $size, $transactions);

while ($stmt->fetch()) {
	$size = format_size($size);

	$txrow .= '
            <tr class="block-member"><td class="blocksAmount"><b>' . $height . '</b></td>
<td class="blocksHash"><a href="/block/' . $hash . '" class="internal transactionLink">' . $hash . '</a></td>
<td class="blocksDate hide-for-small">' . gmdate("M j Y g:i:s A", $time) . '</td>
<td class="blocksAmount hide-for-small" style="text-align: center">' . $transactions . '</td>
<td class="blocksAmount hide-for-small" style="text-align: center">' . $size . '</td>
			</tr>';
}

$stmt->close();

?>
<section>
<div id="logo-region">
  <div class="row">
    <div class="small-8 large-3 large-offset-0 small-offset-2 columns logo">
	<a href="/blockpath/" class="internal"><img src="/blockpath/img/logo_small.png" alt="" /></a>
    </div>
    <div class="large-9 small-12 columns main-search-box" style="margin-bottom: 0">
	<form action="/blockpath/index.php?engine=search" method="POST" >
		<input id="searchBox" name="query" type="text" placeholder="Search for block, transaction or address in " style="font-size: 1.2em;" size="64" />
	</form>
	</div>
</div>
</div>
</section>
</header>
</div></div>
<div id="main-region"><div><div id="blocks-list">
	<section>
        <div id="blocks-region"><div><div class="row">
    <div class="large-12 columns">
        <ul class="breadcrumbs">
            <li><a href="/blockpath/" class="internal">Home</a></li>
            <li class="current">blockchain</li>
            <li class="current"><a href="/blockpath/index.php?engine=blockexplorer" class="internal">Blocks</a></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <h2>Blocks<small class="datepicker calendar" data-date="<?php echo $datepicker; ?>" data-date-format="dd-mm-yyyy" readonly="readonly"> by date <i class="fa fa-calendar"></i></small></h2>
		<?php $html = menudate($dateblocks); echo $html; ?>
        <table class="fullwidth pointerCursor hover">
            <colgroup>
                <col width="0%">
                <col width="100%">
                <col class="hide-for-small" width="0%">
                <col class="hide-for-small" width="0%">
                <col class="hide-for-small" width="0%">
            </colgroup>
            <thead>
                <tr><th class="blocksAmount">Height</th>
                <th class="blocksHash">Hash</th>
                <th class="blocksDate hide-for-small">Solved at</th>
                <th class="blocksAmount hide-for-small">Num Tx</th>
                <th class="blocksAmount hide-for-small">Size</th>
            </tr></thead>
            <tbody>
				<?php echo $txrow; ?>
			</tbody>
        </table>
    </div>
</div>
</div></div>
	</section>
</div>
</div></div>
<div id="push"></div>
</div>