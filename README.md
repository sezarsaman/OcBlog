# OcBlog
Create simple blogs for your shop. It has a huge impact on SEO of your online shop!

How to Install
1.	Upload Files use FTP
2.	Import ocbq.sql file into database
3.	Give access and modify permission to your user group
4.	Add below code to column_left.php inside admin/controller/common after information block for adding the blog link to category sub menu in admin


<code>
  if ($this->user->hasPermission('access', 'blog/post')) {
    $catalog[] = array(
        'name'    => $this->language->get('text_blog'),
        'href'     => $this->url->link('blog/post', 'user_token=' . $this->session->data['user_token'], true),
        'children' => array()
    );
  }
</code>


5.	Access to archive page and post page is available via index.php?route=blog/archive

