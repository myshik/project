<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-category">
      <ul>
        <?php foreach ($blogies as $blog) { ?>
        <li>
          <?php if ($blog['blog_id'] == $blog_id) { ?>
          <a href="<?php echo $blog['href']; ?>" class="active"><?php echo $blog['name']; ?></a>
          <?php } else { ?>
          <a href="<?php echo $blog['href']; ?>"><?php echo $blog['name']; ?></a>
          <?php } ?>
          <?php if ($blog['children']) { ?>
          <ul>
            <?php foreach ($blog['children'] as $child) { ?>
            <li>
              <?php if ($child['blog_id'] == $child_id) { ?>
              <a href="<?php echo $child['href']; ?>" class="active"> - <?php echo $child['name']; ?></a>
              <?php } else { ?>
              <a href="<?php echo $child['href']; ?>"> - <?php echo $child['name']; ?></a>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>
