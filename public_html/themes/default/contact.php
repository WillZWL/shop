<?php $this->load->view('/default/header') ?>
    <div id="contact-container">
        <h1>CONTACT US</h1>
        <h4>We’re here to help you!</h4>
        <div class="text-block">
            Our principal route for all enquiries is via our Sales and Customer Care Team, which has gained extensive experience and knowledge of our products and services in order to serve our customers better. Our goal is to assist you with your queries in a friendly and helpful manner. 
        </div>
        <div class="text-block address">
            Flat/RM 12, 25/F Langham Place Office Tower 8 Argyle Street, Kowloon, Hong Kong
            <br>
            <br>
            Tel: 0870 295 9128
        </div>
        <h4>Email Enquiries</h4>
        <div class="text-block">
            Our ticketing system is designed to ensure that your queries are responded by the most qualified staff and as quickly as possible. In order to take advantage of this, please kindly choose the correct department and the most relevant "Query". Please click link to select a department that you wish to contact below.
        </div>
        <div class="query-form">
            <form method="post" action="/contact/queryForm">
              <div class="form-group">
                <label for="query">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
              </div>
              <div class="form-group">
                <label for="query">Message</label>
                <textarea placeholder="Message" id="message" name="message" class="form-control" rows="3"></textarea>
              </div>
              <div class="form-group">
                <label for="query">Your Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name">
              </div>
              <div class="form-group">
                <label for="query">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
              </div>
              <div class="form-group">
                <label for="query">Order Number</label>
                <input type="text" class="form-control" id="orderNumber" name="orderNumber" placeholder="Order Number">
              </div>
              <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
<?php $this->load->view('/default/footer') ?>
