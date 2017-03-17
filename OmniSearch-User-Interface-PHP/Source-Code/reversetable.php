<table id="results_table" class="table table-bordered">
    <thead>
    <tr>
        <th style="width: 5%">
            <div class="checkbox"><label><input id="select_all" type="checkbox" onchange="selectAll(this)" autocomplete="off"<?php if(count($selected) == $total) echo ' checked';?> disabled/>All</label></div>
        </th>
        <th style="width: 15%; white-space: wrap"><span data-toggle="tooltip" data-placement="top" title="Targeting microRNA">Targeting microRNA</span> <span class="sorting<?php if($sort_col == 'mirna_label') echo $sort_class; ?>" onclick="sort('mirna_label')"></span></th>
        <th style="width: 20%; white-space: wrap"><span data-toggle="tooltip" data-placement="top" title="Links to RNAcentral">RNAcentral</span> </span></th>
        <?php if ($show_mirdb): ?>
        <th style="width: 10%; white-space: nowrap"><span data-toggle="tooltip" data-placement="top" title="Prediction Score from miRDB.">miRDB</span> <span class="sorting<?php if($sort_col == 'mirdb_score') echo $sort_class; ?>" onclick="sort('mirdb_score')"></span></th>
        <?php endif; ?>
        <?php if ($show_targetscan): ?>
        <th style="width: 10%; white-space: nowrap"><span data-toggle="tooltip" data-placement="top" title="Prediction Score from TargetScan.">TargetScan</span> <span class="sorting<?php if($sort_col == 'targetscan_score') echo $sort_class; ?>" onclick="sort('targetscan_score')"></span></th>
        <?php endif; ?>
        <?php if ($show_miranda): ?>
        <th style="width: 10%; white-space: nowrap"><span data-toggle="tooltip" data-placement="top" title="Prediction Score from miRanda.">miRanda</span> <span class="sorting<?php if($sort_col == 'miranda_score') echo $sort_class; ?>" onclick="sort('miranda_score')"></span></th>
        <?php endif; ?>
        <?php if ($show_mirtarbase): ?>
        <th style="width: 10%; white-space: nowrap"><span data-toggle="tooltip" data-placement="top" title="Links to miRTarBase validation page">miRTarBase</span> <span class="sorting<?php if($sort_col == 'mirtarbase_id') echo $sort_class; ?>" onclick="sort('mirtarbase_id')"></span></th>
        <?php endif; ?>
        <th style="width: 10%; white-space: nowrap"><span data-toggle="tooltip" data-placement="top" title="Publications below are filtered according to the gene name">Publications</span> <span class="sorting<?php if($sort_col == 'pubmed_ids') echo $sort_class; ?>" onclick="sort('pubmed_ids')"></span></th>

    </tr>
    </thead>
    <tbody id="results_body">
    <?php $i = ($page - 1) * $rows + 1; ?>
    <?php foreach ($json['results']['bindings'] as $item): ?>
        <tr>
            <td>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="<?php echo $item['mirna_label']['value']; ?>" onchange="onSelect(this)" autocomplete="off"<?php if(in_array($item['mirna_label']['value'], $selected)) echo ' checked'; ?>/><?php echo $i++; ?>
                    </label>
                </div>
            </td>
            <td>
                <?php echo $item['mirna_label']['value']; ?>
            </td>
            <td>
                  <a href="http://rnacentral.org/search?q= <?php echo $item['mirna_label']['value'];?> " target="_blank" data-toggle="tooltip" data-placement="top" title="Link to RNAcentral for  <?php echo $item['mirna_label']['value']; ?>"><span class="glyphicon glyphicon-link"></span></a>

            </td>
            <?php if ($show_mirdb): ?>
                <td>
				        <?php if ($item['mirdb_score']['value'] == $invalid_score): ?>
                    <p>-</p>
                    <?php else: ?>
                    <a href="http://mirdb.org/cgi-bin/search.cgi?species=Human&searchBox=<?php echo $item['mirna_label']['value']; ?>&submitButton=Go&searchType=miRNA" target="_blank" data-toggle="tooltip" data-placement="top" title="Link to miRDB results for  <?php echo $item['mirna_label']['value']; ?>"><?php echo round($item['mirdb_score']['value']); ?></a>
                    <?php endif; ?>          
					</td>
                
            <?php endif; ?>
            <?php if ($show_targetscan): ?>
                <td>
					<?php if ($item['targetscan_score']['value'] == $invalid_score): ?>
                    <p>-</p>
                    <?php else: ?>
                    <a href="http://www.targetscan.org/cgi-bin/targetscan/vert_70/targetscan.cgi?species=Human&gid=&mir_sc=&mir_c=&mir_nc=&mirg=<?php echo $item['mirna_label']['value']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Link to TargetScan results for  <?php echo $item['mirna_label']['value']; ?>"><?php echo round($item['targetscan_score']['value']); ?></a>
                    <?php endif; ?>          
					</td>
            <?php endif; ?>
            <?php if ($show_miranda): ?>
                <td>
					<?php if ($item['miranda_score']['value'] == $invalid_score): ?>
                    <p>-</p>
                    <?php else: ?>
                    <a href="http://www.microrna.org/microrna/getTargets.do?matureName=<?php echo $item['mirna_label']['value']; ?>&organism=9606" target="_blank" data-toggle="tooltip" data-placement="top" title="Link to miRanda results for  <?php echo $item['mirna_label']['value']; ?>"><?php echo $item['miranda_score']['value']; ?></a>
                    <?php endif; ?>           
					</td>
            <?php endif; ?>
            <?php if ($show_mirtarbase): ?>
                <td>
                    <?php if ($item['mirtarbase_id']['value'] == ''): ?>
                    <p>-</p>
                    <?php else: ?>
                    <a href="http://mirtarbase.mbc.nctu.edu.tw/php/detail.php?mirtid=<?php echo $item['mirtarbase_id']['value']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Link to miRTarBase validation page for <?php echo $item['mirna_label']['value'];echo ':::';echo $gene; ?>"><span class="glyphicon glyphicon-link"></span></a>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
            <td>
                <?php if ($item['pubmed_ids']['value'] == ''): ?>
                    <span>-</span
                <?php else: ?>
                    <?php $count = substr_count($item['pubmed_ids']['value'], ','); ?>
                    <?php if($count > 0) $pubmed_str = explode(',', $item['pubmed_ids']['value'])[0] . ' + ' . $count . ' More'; else $pubmed_str = $item['pubmed_ids']['value']; ?>
                    <a href="http://www.ncbi.nlm.nih.gov/pubmed?term=<?php echo $item['pubmed_ids']['value']; ?>" target="_blank" style="white-space: nowrap"><?php echo $pubmed_str; ?></a>
                <?php endif; ?>
            </td>
			
			
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>