<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Sitemap extends PUB_Controller
{

    public function Sitemap()
    {
        parent::PUB_Controller();
        $this->load->helper(array('url'));
        //$this->load->model('marketing/category_model');
    }

    public function index()
    {
        # this function will read in the sitemap created by google sitemap tool
        # and group those similar links together to produce a better sitemap.xml

        $xmlstring = "";

        $filename = "web_sitemap_1d87bdf4_000.xml.gz";
        $zh = gzopen("$filename", 'r') or die("can't open: $php_errormsg");
        while ($line = gzgets($zh, 1024)) {
            // $line is the next line of uncompressed data, up to 1024 bytes
            $xmlstring .= $line;
        }
        gzclose($zh) or die("can't close: $php_errormsg");

        #$xmlstring = file_get_contents("web_sitemap_1d87bdf4_000.xml");
        $src = simplexml_load_string($xmlstring);

        $i = 0;
        foreach ($src->url as $url) {
            $i++;
            $allow = true;
            $hreflang = false;
            $urlstring = "{$url->loc}";

            $blockeditem = "/\/[a-z][a-z]_[A-Z][A-Z]($|\/)/";   # vb.com/en_SG/
            if (preg_match($blockeditem, $urlstring, $lang)) $hreflang = true;
            #       if (!$hreflang)
            #       {
            #           $blockeditem = "/\/[a-z][a-z]_[A-Z][A-Z]$/";    # vb.com/en_SG
            #           if (preg_match($blockeditem, $urlstring, $lang)) $hreflang = true;
            #       }

            $blockeditem = "/.(gif|svgz|svg|jpeg|jpg|png|woff|eot|ico|ttf)$/i";
            if (preg_match($blockeditem, $urlstring)) $allow = false;

            $blockeditem = "/cart\/add_item/i";
            if (preg_match($blockeditem, $urlstring)) $allow = false;

            $blockeditem = "/cart\/ajax_rm_cart/i";
            if (preg_match($blockeditem, $urlstring)) $allow = false;

            $blockeditem = "/cart\/ajax_add_cart/i";
            if (preg_match($blockeditem, $urlstring)) $allow = false;

            $blockeditem = "/stock_feed\/xml_stock_feed/i";
            if (preg_match($blockeditem, $urlstring)) $allow = false;

            $blockeditem = "/(^|\/)(search|sli|login|logout|deleted_MY|review_order|myaccount)(?|\/|$)/i";
            if (preg_match($blockeditem, $urlstring)) $allow = false;


            $blockeditem = "/(^|\/)(mainproduct|display\/view)(\/|$)/i";
            if (preg_match($blockeditem, $urlstring)) $allow = false;

            if (1 == 1) {
                $blockeditem = "/http:\/\/www.valuebasket.com/i";
                $urlstring = preg_replace($blockeditem, "", $urlstring);

                $langloc = "****************************";
                $langloc = "en";    #default is english
                if ($hreflang) {
                    $langloc = trim($lang[0], "/");
                    $langloc = substr($langloc, 0, 2);

                    $blockeditem = "/\/[a-z][a-z]_[A-Z][A-Z]($|\/)/";   # vb.com/en_SG/
                    $urlstring = preg_replace($blockeditem, "", $urlstring);
                }
            }

            if ($allow) {
                $u["loc"] = urldecode($url->loc);
                $u["hash"] = urldecode(trim($urlstring, "/"));
                $u["lastmod"] = $url->lastmod;
                $u["changefreq"] = $url->changefreq;
                $u["priority"] = $url->priority;
                $u["hreflang"] = $langloc;

                $urllist[] = $u;
            }
        }

        usort($urllist, array($this, 'cmp'));

        $i = 0;
        foreach ($urllist as $urlstring) {
            $i++;

            #if ($prevhash != $urlstring['hash']) echo "<HR>";
            $prevhash = $urlstring['hash'];
            $urlstring['loc'] = urldecode($urlstring['loc']);
            #echo "$i {$urlstring['hreflang']} | {$urlstring['hash']}<br>";
        }


        $humanxml = true;
        $humanxml = false;

        $dst = new SimpleXMLElement('<urlset/>');
        if (!$humanxml) {
            $dst->addAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
            $dst->addAttribute("xmlns:xmlns:xhtml", "http://www.w3.org/1999/xhtml");
        }

        $mainhash = "";
        foreach ($urllist as $key => $urlstring) {
            if ($mainhash != $urlstring['hash']) {
                # add the parent
                $mainloc = $urlstring['loc'];
                $mainhash = $urlstring['hash'];

                if ($urllist[$key + 1]["hash"] == $mainhash) {
                    # we have a child
                    $url = $dst->addChild("url");
                    $url->loc = $urlstring["loc"];
                    $url->lastmod = $urlstring["lastmod"];
                    $url->changefreq = $urlstring["changefreq"];
                    $url->priority = $urlstring["priority"];
                }
            } else {
                # add the equivalent (if any)
                if (!$humanxml)
                    $xhtml = $url->addChild("xhtml:xhtml:link");
                else
                    $xhtml = $url->addChild("link");
                $xhtml->addAttribute("rel", "alternate");
                $xhtml->addAttribute("hreflang", $urlstring['hreflang']);
                $xhtml->addAttribute("href", $urlstring['loc']);
            }
        }

        $xmlstring = $dst->asXML();
        $fp = fopen("sitemap.xml", "w+");
        fwrite($fp, $xmlstring);
        fclose($fp);

        #submit the sitemap to google
        file_get_contents("http://www.google.com/webmasters/tools/ping?sitemap=http://www.valuebasket.com/sitemap.xml");

        header('Content-type: text/xml');
        echo $xmlstring;

        die();

        //$data['cat_list'] = $this->category_model->get_listed_cat_tree();
        show_404();
        $this->load_view('sitemap.php', $data);
    }

    private function cmp($a, $b)
    {
        $field = "hash";

        if ($a[$field] == $b[$field]) return 0;

        $ret = (($a[$field] < $b[$field]) ? -1 : 1);

        $ascending = false;
        if ($ascending) return $ret; else return -$ret;
    }

}
