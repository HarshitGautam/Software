<table id="results_table" class="table table-bordered">
    <thead>
    <tr>
        <th style="width: 5%">
            <div class="checkbox"><label><input id="select_all" type="checkbox" onchange="selectAll(this)" autocomplete="off"<?php if(count($selected) == $total) echo ' checked';?> disabled/>All</label></div>
        </th>
        <th style="width: 15%; white-space: nowrap"><span data-toggle="tooltip" data-placement="top" title="miRNA Target Gene Symbol">Candidate Target</span> <span class="sorting<?php if($sort_col == 'gene_symbol') echo $sort_class; ?>" onclick="sort('gene_symbol')"></span></th>
        <th style="width: 25%; white-space: nowrap"><span data-toggle="tooltip" data-placement="top" title="GO Annotations for Candidate Targets">Target Functional Annotations</span> <span class="sorting<?php if($sort_col == 'gene_name') echo $sort_class; ?>" onclick="sort('gene_name')"></span></span></th>
        <?php if ($show_mirdb): ?>
            <th style="width: 10%; white-space: nowrap"><a href="http://mirdb.org/cgi-bin/search.cgi?species=Human&searchBox=<?php echo $mirna; ?>&submitButton=Go&searchType=miRNA" target="_blank" data-toggle="tooltip" data-placement="top" title="Link to miRDB results for <?php echo $mirna; ?>">miRDB</a><span id="mirdb_sort" class="sorting<?php if($sort_col == 'mirdb_score') echo $sort_class; ?>" onclick="sort('mirdb_score')"></span></th>
        <?php endif; ?>
        <?php if ($show_targetscan): ?>
            <th style="width: 10%; white-space: nowrap"><a href="http://www.targetscan.org/cgi-bin/targetscan/vert_70/targetscan.cgi?species=Human&gid=&mir_sc=&mir_c=&mir_nc=&mirg=<?php echo $mirna; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Link to TargetScan results for <?php echo $mirna; ?>">TargetScan</a><span class="sorting<?php if($sort_col == 'targetscan_score') echo $sort_class; ?>" onclick="sort('targetscan_score')"></span></th>
        <?php endif; ?>
        <?php if ($show_miranda): ?>
            <th style="width: 10%; white-space: nowrap"><a href="http://www.microrna.org/microrna/getTargets.do?matureName=<?php echo $mirna; ?>&organism=9606" target="_blank" data-toggle="tooltip" data-placement="top" title="Link to miRanda results for <?php echo $mirna; ?>">miRanda</a><span class="sorting<?php if($sort_col == 'miranda_score') echo $sort_class; ?>" onclick="sort('miranda_score')"></span></th>
        <?php endif; ?>
        <?php if ($show_mirtarbase): ?>
            <th style="width: 10%; white-space: nowrap"><a href="http://mirtarbase.mbc.nctu.edu.tw/php/search.php?opt=search_box&kw=<?php echo $mirna; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Link to miRTarBase results for <?php echo $mirna; ?>">miRTarBase</a><span class="sorting<?php if($sort_col == 'mirtarbase_score') echo $sort_class; ?>" onclick="sort('mirtarbase_id')"></span></th>
        <?php endif; ?>
        <th style="width: 15%; white-space: nowrap"><span data-toggle="tooltip" data-placement="top" title="Publications below are filtered according to the microRNA name, candidate target at each row, as well as the optional MeSH term.">Publications</span> <span class="sorting<?php if($sort_col == 'pubmed_ids') echo $sort_class; ?>" onclick="sort('pubmed_ids')"></span></th>
    </tr>
    </thead>
    <tbody id="results_body">
    <?php $i = ($page - 1) * $rows + 1; ?>
    <?php foreach ($json['results']['bindings'] as $item): ?>
        <tr>
            <td>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="<?php echo $item['gene_symbol']['value']; ?>" onchange="onSelect(this)" autocomplete="off"<?php if(in_array($item['gene_symbol']['value'], $selected)) echo ' checked'; ?>/><?php echo $i++; ?>
                    </label>
                </div>
            </td>
            <td>
                <?php echo $item['gene_symbol']['value']; ?>
            </td>
            <td>
                <?php if ($item['amigo_count']['value'] > 0): ?>
                    <a href="http://amigo.geneontology.org/amigo/search/annotation?fq=taxon_label:%22Homo%20sapiens%22&q=%22<?php echo $item['gene_symbol']['value']; ?>%22" target="_blank" data-toggle="tooltip" data-placement="top" title="Link to GO Annotations page for <?php echo $item['gene_symbol']['value']; ?>"><?php echo $item['gene_name']['value']; ?></a>
                <?php else: ?>
                    <span><?php echo $item['gene_name']['value']; ?></span>
                <?php endif; ?>
            </td>
            <?php if ($show_mirdb): ?>
                <td>
                    <?php if ($item['mirdb_score']['value'] == $invalid_score) echo '-'; else echo '<span data-toggle="tooltip" data-placement="top" title="Prediction score from miRDB">' . round($item['mirdb_score']['value']) . '</span>'; ?>
                </td>
            <?php endif; ?>
            <?php if ($show_targetscan): ?>
                <td>
                    <?php if ($item['targetscan_score']['value'] == $invalid_score) echo '-'; else echo '<span data-toggle="tooltip" data-placement="top" title="Prediction score from TargetScan">' . round($item['targetscan_score']['value']) . '</span>'; ?>
                </td>
            <?php endif; ?>
            <?php if ($show_miranda): ?>
                <td>
                    <?php if ($item['miranda_score']['value'] == $invalid_score) echo '-'; else echo '<span data-toggle="tooltip" data-placement="top" title="Prediction score from miRanda">' . $item['miranda_score']['value'] .'</span>'; ?>
                </td>
            <?php endif; ?>
            <?php if ($show_mirtarbase): ?>
                <td>
                    <?php if ($item['mirtarbase_id']['value'] == ''): ?>
                    <p>-</p>
                    <?php else: ?>
                    <a href="http://mirtarbase.mbc.nctu.edu.tw/php/detail.php?mirtid=<?php echo $item['mirtarbase_id']['value']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Link to miRTarBase validation page for <?php echo $item['gene_symbol']['value']; ?>"><span class="glyphicon glyphicon-link"></span></a>
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