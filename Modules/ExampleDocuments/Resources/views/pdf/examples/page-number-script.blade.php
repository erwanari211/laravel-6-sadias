{{-- Page number --}}
<script type="text/php">
  if (isset($pdf)) {
    /**
     * Position
     * left {35} center {250} right {560}
     * top {10} bottom {794}
     */

    $x = 250;
    $y = 800;
    $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
    $font = null;
    $size = 10;
    $color = array(0,0,0);
    $word_space = 0.0;  //  default
    $char_space = 0.0;  //  default
    $angle = 0.0;   //  default
    $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
  }
</script>
