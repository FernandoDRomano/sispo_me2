<?php if($this->uri->uri_string != "backend/auth/login") : ?>
  <ol class="breadcrumb">
    <?php if($this->uri->segment(2) == "dashboard"){ ?>
      <li><a href="<?php echo base_url().'backend/dashboard' ?>"><i class="fa fa-home"></i> Dashboard</a></li>
    <?php }else{ ?>
      <li><a href="<?php echo base_url().'backend/dashboard' ?>"><i class="fa fa-home"></i> Dashboard</a></li>
      <?php 
        $urls = $this->uri->segment_array(); 
        foreach ($urls as $f) {
          $array_breadcrumb = sudaca_url_segmentos(); 
          $url = str_replace(array_keys($array_breadcrumb), array_values($array_breadcrumb), $f);
          if ($f != 'backend' && $f != 'ecommerce' && $f != 'cms') {
            ?><li><a href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2) ?>"><?php echo ucwords($url) ?></a></li><?php
          }          
        }
      } 
    ?>
  </ol>
<?php endif ?>