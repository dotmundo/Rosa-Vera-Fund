<form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
    <div>
    <input type="text" class="field" name="s" id="s"  value="<?php _e('Search...',woothemes); ?>" onfocus="if (this.value == '<?php _e('Search...',woothemes); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Search...',woothemes); ?>';}" />
    <input type="hidden" class="submit" name="submit" />
    </div>
</form>