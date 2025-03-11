<?php
if (!isset($fontAwesomeLoaded)) { ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?php } ?>

<footer class="footer mt-auto py-3 bg-dark text-white">
    <div class="mdx-section">
        <!-- <div class="row g-4 mdx-foot"> -->
        <div class="mdx-foot">
        <div class="col-12 col-md-3 d-box">
                <h5 class="footer-heading">About Kwetu</h5>
                <!-- <img src="../assets/logo-full.svg" alt="Kwetu Auctions" class="footer-logo"> -->
                <p style="font-size: small !important" class="text-muted mdx-footer-text mdx-foot-toes">
Welcome to the ultimate destination for online auctions in Uganda! Whether you're a seasoned 
bidder or new to the world of auctions, our platform is designed to 
provide you with a seamless, secure, and exciting experience. We bring together buyers and sellers 
from across the country, offering a diverse range of quality items at unbeatable prices.                </p>
            </div>
            <div class="col-12 col-md-3 d-box">
                <h5 class="footer-heading">Quick Links</h5>
                <ul class="list-unstyled footer-links">
                    <li><a style="font-size: small !important" href="/auction_guide.php" class="text-muted mdx-footer-text">Auction Guide</a></li>
                    <li><a style="font-size: small !important" href="/transport_services.php" class="text-muted mdx-footer-text">Transport Services</a></li>
                    <li><a style="font-size: small !important" href="/sell_with_us.php" class="text-muted mdx-footer-text">Sell With Us</a></li>
                    <li><a style="font-size: small !important" href="/faq.php" class="text-muted mdx-footer-text">FAQ</a></li>
                </ul>
            </div>
            <div class="col-12 col-md-3 d-box">
                <h5 class="footer-heading">Contact Us</h5>
                <ul class="list-unstyled contact-info text-muted">
                    <li class="mdx-footer-text"><i  class="fas fa-map-marker-alt"></i> <p style="font-size: small !important; margin-bottom: 0px !important;">Kampala, Uganda</p></li>
                    <li class="mdx-footer-text"><i class="fas fa-phone"></i><p style="font-size: small !important; margin-bottom: 0px !important;">+256 756 027 405</p></li>
                    <li class="mdx-footer-text"><i class="fas fa-envelope"></i> <p style="font-size: small !important; margin-bottom: 0px !important;">info@kwetuauctions.com</p> </li>
                </ul>
            </div>
            <div class="col-12 col-md-3 d-box">
                <h5 class="footer-heading">Follow Us</h5>
                <div class="social-links">
                    <a  href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-4 footer-divider">
        <div class="copyright-section">
            <div class="col-12">
                <p class="mb-0 text-muted text-center copyright-text mdx-footer-text">
                    &copy; <?php echo date('Y'); ?> Kwetu Auctions. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>

<style>




.footer {
    padding-top: 6rem !important;
    padding-bottom: 1rem !important;
    margin-top: 3rem;
    position: relative;
    bottom: 0;
    background-color: black !important;
    width: 100%;
}

.copyright-section{
    width: fit-content;
    display: flex; 
    width: 100%;
    justify-items: center;
    align-items: center;
}

.mdx-section{
    text-align: left !important;
}

.footer-logo{
    width: 40%;
    margin-bottom: 1rem
}

.mdx-foot-toes{
    width: 20rem
}

.mdx-foot{
    gap: 8rem;
    /* background-color: green !important; */
    display: flex;
  justify-content: center;
}

.d-box{
    /* background-color: red !important; */
    width: fit-content;
}

.mdx-footer-text {
    color: #bbb !important;
    margin-bottom: 1rem !important;
}

.footer-heading {
    /* color: #f78b00; */
    color: white;
    font-size: 1rem;
    margin-bottom: 1.5rem;
    font-weight: 800;
}

.footer-links li {
    margin-bottom: 1rem;
}

.footer-links a {
    text-decoration: none;
    transition: color 0.3s ease;
    font-size:small;
}

.footer-links a:hover {
    color: #f78b00 !important;
}

.contact-info li {
    margin-bottom: 0rem;
    font-size:small;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.contact-info i {
    color: #bbb
;
    width: 20px;
}

.social-links {
    display: flex;
    gap: 1.2rem;
}

.social-link {
    color: #bbb;
    font-size: small;
    transition: color 0.3s ease;
}

.social-link:hover {
    color: #f78b00;
}

.small-text {
    font-size: small;
    line-height: 1.6;
}

.footer-divider {
    border-color: white;
    margin: 2.5rem 0;
}

.copyright-text {
    text-align: center !important;
    margin-bottom: 2rem !important;
    font-size: small;
}

/* Responsive Styles */
@media (max-width: 768px) {

.mdx-section {
  text-align: left !important;
  display: flex;
  flex-direction: column;
}
.d-box {
  width: fit-content;
  margin-left: 2rem;
}


.mdx-foot {
  gap: 1rem;
  display: flex;
  justify-content: center;
}

.mdx-foot {
  display: flex;
  flex-direction: column;
}

    .footer {
        text-align: center;
        padding: 2rem 1rem;
    }
    
    .footer-heading {
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
    
    .contact-info {
        display: inline-block;
        text-align: left;
    }
    
    .contact-info li {
        justify-content: left;
    }
    
    .social-links {
        justify-content: center;
        margin-top: 1rem;
    }
    
    .footer-links {
        margin-bottom: 1.5rem;
    }
    
    .footer-links li {
        margin-bottom: 0.8rem;
    }
    

    
    .row.g-4 > * {
        margin-bottom: 2rem;
    }
}

/* Extra small devices */
@media (max-width: 576px) {
    .footer {
        padding: 1.5rem 0.5rem;
    }
    
    .footer-heading {
        font-size: 1rem;
    }
    
    .small-text, 
    .footer-links a, 
    .copyright-text,
    .contact-info li {
        font-size: small;
    }
    
}
</style>
