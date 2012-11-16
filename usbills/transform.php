<?php
/*
Copyright 2002-2012 MarkLogic Corporation.  All Rights Reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

// Script for transforming US bills into simpler versions for search demo

$rootdir = 'bills';
$subdirs = array('110_orig', '111_orig', '112_orig');

foreach($subdirs as $subdir) {
    $count = 0;
    $dir = $rootdir . '/' . $subdir;
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if (substr($file, 0, 1) !== ".") {
                $content = file_get_contents($dir . '/' . $file);

                $old = new DOMDocument();
                $old->loadXML($content);
                $xp = new DOMXpath($old);

                $new = new DOMDocument();

                $billElem = $new->createElement('bill');
                $session = $xp->query('//bill/@session')->item(0)->nodeValue;
                $billElem->setAttribute('session', $session);
                $type = $xp->query('//bill/@type')->item(0)->nodeValue;
                $billElem->setAttribute('type', $type);
                $number = $xp->query('//bill/@number')->item(0)->nodeValue;
                $billElem->setAttribute('number', $number);
                $abbrev = (($type == 'h') ? 'H.R.' : 'S.') . ' ' . $number;
                $billElem->setAttribute('abbrev', $abbrev);
                $new->appendChild($billElem);

                $statusElem = $new->createElement('status');
                $status = $xp->query('//status/*[1]/@datetime')->item(0)->nodeValue;
                if (empty($status)) {
                    continue;
                }
                $statusElem->setAttribute('date', $status);
                $statusElem->nodeValue = $xp->query('//status/*[1]')->item(0)->nodeName;
                $billElem->appendChild($statusElem);

                $introducedElem = $new->createElement('introduced');
                $introducedElem->setAttribute('date', $xp->query('//introduced/@datetime')->item(0)->nodeValue);
                $billElem->appendChild($introducedElem);

                $titleElem = $new->createElement('title');
                $title_short = $xp->query("//title[@type = 'short']")->item(0)->nodeValue;
                $title_official = $xp->query("//title[@type = 'official']")->item(0)->nodeValue;
                echo $title_short . '<br />' . $title_official . '<br />';
                $title = (empty($title_short)) ? $title_official : $title_short;
                $titleElem->nodeValue = $title;
                $billElem->appendChild($titleElem);

                $relatedElem = $new->createElement('relatedbills');
                    foreach ($xp->query("//relatedbills/bill") as $rel) {
                        $relbillElem = $new->createElement('relatedbill');
                        $relbillElem->setAttribute('session', $rel->getAttribute('session'));
                        $relbillElem->setAttribute('type', $rel->getAttribute('type'));
                        $relbillElem->setAttribute('number', $rel->getAttribute('number'));
                        $relatedElem->appendChild($relbillElem);
                    }
                $billElem->appendChild($relatedElem);

                $subjectsElem = $new->createElement('subjects');
                    foreach ($xp->query("//subjects/term") as $sub) {
                        $subjectElem = $new->createElement('subject');
                        $subjectElem->nodeValue = $sub->getAttribute('name');
                        $subjectsElem->appendChild($subjectElem);
                    }
                $billElem->appendChild($subjectsElem);

                $summaryElem = $new->createElement('summary');
                $summaryElem->nodeValue = trim(htmlspecialchars($xp->query("//summary")->item(0)->nodeValue));
                $billElem->appendChild($summaryElem);

                $linkElem = $new->createElement('link');
                $link = 'http://thomas.loc.gov/cgi-bin/query/z?c';
                $link .= $session . ':';
                $link .= ($type === 'h') ? 'H.R.' : 'S.';
                $link .= $number;
                $link .= ':';
                $linkElem->setAttribute('href', $link);
                $billElem->appendChild($linkElem);

                // Only write bills that have related and have short (but not empty) titles
                $num_rel_bills = $new->getElementsByTagName('relatedbill')->length;
                $len_title = strlen($new->getElementsByTagName('title')->item(0)->nodeValue);
                if ($num_rel_bills == 0 || $len_title > 80 || strlen($title) == 0) {
                    continue;
                }
                // Write content file system
                file_put_contents($dir . 't/' . $file, $new->saveXML());
                echo $dir . '/' . $file . '<br />';
                $count++;
                if ($count >= 100) {
                    //break;
                }
                //exit;
            }
        }
        closedir($handle);
    }
}

?>

</body>
</html>