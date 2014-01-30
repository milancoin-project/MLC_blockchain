<section>
<div id="logo-region">
  <div class="row">
    <div class="small-8 large-3 large-offset-0 small-offset-2 columns logo">
	<a href="http://www.mmc-chain.com" class="internal"><img src="/img/logo_small.png" alt="" /></a>
    </div>
    <div class="large-9 small-12 columns main-search-box" style="margin-bottom: 0">
	<form action="/?engine=search" method="POST" >
		<input id="searchBox" name="query" type="text" placeholder="Search for block, transaction or address in " style="font-size: 1.2em;" size="64" />
	</form>
	</div>
</div>
</div>
</section>
</header>
<div id="main-region">
<div class="row">
<div class="large-12 columns">
		<h3>Official Nodes</h3>
        <table class="fullwidth pointerCursor hover">
            <colgroup>
                <col width="20%">
				<col width="10%">
                <col width="30%">
                <col width="20%">
                <col width="10%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr>
					<th class="blocksAmount">IP</th>
					<th class="blocksAmount">PORT</th>
					<th class="blocksAmount hide-for-small">DNS</th>
					<th class="blocksAmount hide-for-small">Geographic Location</th>
					<th class="blocksAmount hide-for-small">Churn</th>
					<th class="blocksAmount">STATUS</th>
				</tr>
			</thead>
            <tbody>
<?php
	$offnode = "";
	$outnode = "";
	
	$stmt = $mysqli->prepare("SELECT * FROM `nodes` ORDER BY `id` ASC");
	$stmt->execute();
	$stmt->bind_result($id, $ip, $port, $dns, $geo, $nodechurn, $official, $nodestatus);

	while ($stmt->fetch()) {
		if ($nodestatus == 1) { $status = "node.png"; } else { $status = "no_node.png"; }
		if ($nodechurn == 1) { $churn = "churn.png"; } else { $churn = "no_churn.png"; }
		
		if ($official == 1) {
			$offnode .= '
            <tr class="block-member">
				<td class="blocksAmount"><b>' . $ip . '</b></td>
				<td class="blocksAmount"><b>' . $port . '</b></td>
				<td class="blocksAmount hide-for-small">' . $dns . '</td>
				<td class="blocksAmount hide-for-small">' . $geo . '</td>
				<td class="blocksAmount hide-for-small"><img src="/img/' . $churn . '" alt="" /></td>
				<td class="blocksAmount"><img src="/img/' . $status . '" alt="" /></td>
			</tr>';
		} else {
			$outnode .= '
            <tr class="block-member">
				<td class="blocksAmount"><b>' . $ip . '</b></td>
				<td class="blocksAmount"><b>' . $port . '</b></td>
				<td class="blocksAmount hide-for-small"><a href="https://bitsharestalk.org/index.php?action=profile;user=' . $dns . '" target="_blank">' . $dns . '</a></td>
				<td class="blocksAmount hide-for-small">' . $geo . '</td>
				<td class="blocksAmount hide-for-small"><img src="/img/' . $churn . '" alt="" /></td>
				<td class="blocksAmount"><img src="/img/' . $status . '" alt="" /></td>
			</tr>';
		}
	}
	
	$stmt->close();
	
	echo $offnode;
?>
			</tbody>
        </table>
		<br />
		<h3>Additional Nodes</h3>
        <table class="fullwidth pointerCursor hover">
            <colgroup>
                <col width="20%">
				<col width="10%">
                <col width="30%">
                <col width="20%">
                <col width="10%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr>
					<th class="blocksAmount">IP</th>
					<th class="blocksAmount">PORT</th>
					<th class="blocksAmount hide-for-small">USER</th>
					<th class="blocksAmount hide-for-small">Geographic Location</th>
					<th class="blocksAmount hide-for-small">Churn</th>
					<th class="blocksAmount">STATUS</th>
				</tr>
			</thead>
            <tbody>
				<?php echo $outnode; ?>
			</tbody>
        </table>		
	</div>
</div>
</div>
<div id="push"></div>
<br /><br /><br /><br />
</div>