<?php
if (!defined('IN_CB')) { die('You are not allowed to access to this page.'); }
?>

@foreach($Item as $row)
<img src="/barcodegen/html/image.php?filetype=PNG&dpi=72&thickness=30&scale=4&rotation=0&font_family=Arial.ttf&font_size=8&start=NULL&text={{$row->item_barcode}}" alt="Barcode Image" />
@endforeach


            <div class="output">
                <section class="output">
                    <h3>Output</h3>
                    <?php
                        $imageKeys='';
                        $finalRequest = '';
                        foreach (getImageKeys() as $key => $value) {
                            $finalRequest .= '&' . $key . '=' . urlencode($value);
                        }
                        if (strlen($finalRequest) > 0) {
                            $finalRequest[0] = '?';
                        }
                        // echo $finalRequest;
                    ?>
                    <div id="imageOutput">
                        <?php if (isset($_REQUEST['text']) && $_REQUEST['text']!== '') { ?>
                            <img src="/barcodegen/html/image.php<?php echo $finalRequest; ?>" alt="Barcode Image" />
                            <?php }
                        else { ?>Fill the form to generate a barcode.<?php } ?>
                    </div>
                </section>
            </div>
        </form>

        <!-- <div class="footer">
            <footer>
            All Rights Reserved &copy; <?php date_default_timezone_set('UTC'); echo date('Y'); ?> <a href="http://www.barcodephp.com" target="_blank">Barcode Generator</a>
            <br /><?php echo $code; ?> PHP5-v<?php //echo $codeVersion; ?>
            </footer>
        </div> -->
    </body>
</html>