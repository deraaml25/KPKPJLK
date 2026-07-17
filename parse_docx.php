<?php
$file = 'Ceklist Dokumen Pengangkatan, Rotasi dan Pemberhentian.docx';
$zip = new ZipArchive;

if ($zip->open($file) === TRUE) {
    if (($index = $zip->locateName('word/document.xml')) !== false) {
        $data = $zip->getFromIndex($index);
        $zip->close();

        $dom = new DOMDocument();
        $dom->loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
        $text = '';

        $paragraphs = $dom->getElementsByTagName('p');
        foreach ($paragraphs as $p) {
            $texts = $p->getElementsByTagName('t');
            $p_text = '';
            foreach ($texts as $t) {
                $p_text .= $t->nodeValue;
            }
            if ($p_text !== '') {
                $text .= $p_text . "\n";
            }
        }

        file_put_contents('ceklist_extracted.txt', $text);
        echo "Successfully extracted to ceklist_extracted.txt";
    } else {
        echo "Could not find word/document.xml";
    }
} else {
    echo "Could not open ZIP archive.";
}
