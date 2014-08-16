<?php
  define("BASE_URL", getBasePath());
  define("CDN_URL", getCDNPath());

  function getBasePath()
  {
    $s = &$_SERVER;
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
    $uri = $protocol . '://' . $host /*. $port*/ . $s['REQUEST_URI'];
    $segments = explode('?', $uri, 2);
    $url = $segments[0];
    return $url;
  }

  function getCDNPath()
  {
    if (getenv("CDNBASE") != "")
    {
        $version_file = json_decode(file_get_contents("../version.json"));
        return getenv("CDNBASE") . $version_file->version . "/";
    }
    else
    {
        return "";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Taxee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Meet Taxee - Your Friendly Neighborhood Income Tax Data and Calculation API.">
    <meta name="keywords" content="tax API, income tax, taxee, tax data, restful tax api">
    <meta name="author" content="Andrew Hass">
    <link rel="shortcut icon" href="<?php echo getCDNPath() ?>assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo getCDNPath() ?>assets/img/favicon.ico" type="image/x-icon">
    <!-- Le styles -->
    <link href="<?php echo getCDNPath() ?>assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo getCDNPath() ?>assets/css/styles.css" rel="stylesheet">
    <link href="<?php echo getCDNPath() ?>assets/css/font-awesome.min.css" rel="stylesheet">
    <!-- Les Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
    <style>
      body {
        padding-top: 60px;
      }
    </style>
    <link href="<?php echo getCDNPath() ?>assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- JQuery -->
    <script type="text/javascript" src="<?php echo getCDNPath() ?>assets/js/jquery.js"></script>
    <script>
      var BASE_URL = "<?php echo BASE_URL ?>";
    </script>
  </head>

  <body>
    <!-- Introduction Text and Responsive Image -->
    <div class="container">
        <div class="row-fluid" >
            <div class="span5 offset1">
                <div class="taxee-header">
                    <h1>MEET TAXEE </h1>
                    <h2 class="notinyview">Your Friendly Neighborhood Income Tax Data and Calculation API.</h2>
                </div>
            </div><!-- span6 -->
            <div class="span6">
                <div class="calculator">
                <img id="calculator-img" src="<?php echo getCDNPath() ?>assets/img/calculator/hello.png" alt="calculator" />
                 <h2 class="tinyview">Your Friendly Neighborhood Income Tax Data and Calculation API.</h2>
            </div>
            </div><!-- span6 -->

        </div><!-- row -->
        <br />
        <br />
        <br />
    </div> <!-- /container -->
    <div id="welcomewrap">
        <div class="container">
            <div class="row">
            <div class="span12">
                <div class="text-center">
                <h3>Taxee is a RESTful Web API that loves telling you about income taxes. </h3>
                <br />
                <button id="testtaxee" class="btn-large btn-inverse">Click here to see what he can do!</button>
                <br />
                <div id="user-block" class="row-fluid">
                    <div id="user-image" class="span6">
                        <div class="avatar" class="">
                            <img id="avatar" />
                        </div>
                    </div>
                    <div id="user-story" class="text-left span6">
                        <div id="user-story-inner">
                        </div>
                    </div>

                            </div>
                        <br />
                    </div>
                </div>
            </div>
        </div>
    </div>

  <div id="servicewrap">
    <div class="container">
      <div class="row">
            <div class="span4">
                <div class="mask">
                    <h4><img src="<?php echo getCDNPath() ?>assets/img/icons/taxee/map.png" alt="state" />  State Data</h4>
                    <p>Taxee knows income tax at the state level.  He can give you info about marginal rates and deductions for all 50 US states!</p>
                </div>
            </div><!-- span3 -->

            <div class="span4">
                <div class="mask">
                    <h4><img src="<?php echo getCDNPath() ?>assets/img/icons/taxee/courthouse.png" alt="federal" />  Federal Data</h4>
                    <p>He also knows all about federal income taxes.  He can provide marginal rate and deduction info at this level, as well!</p>
                </div>
            </div><!-- span3 -->

            <div class="span4">
                <div class="mask">
                    <h4><img src="<?php echo getCDNPath() ?>assets/img/icons/taxee/math.png" alt="federal" />  Calculations!</h4>
                    <p>Taxee loves math!  Give him a filing status and an annual income and he'll tell you what the income tax amount will be. </p>
                </div>
            </div><!-- span3 -->

        </div><!-- row -->
    </div><!-- container -->
  </div><!-- servicewrap -->


    <!-- Single Item Image -->
    <br>
  <br>

    <div id="apiwrap">
        <div class="container">
            <div class="row">
                <div class="span12">
                    <h2>API</h2>
                    <p style="margin: 20px 8px;">The endpoint for the API is <b>http://taxee.io/api</b>.  All URLs mentioned below are relative to the endpoint.</p>
                    <div id="mashape-container-270675657" style="width: 100%;"><div id="mashape-doc" style="display: none;">

<div class="mashape-group">
 <div class="mashape-group-header"></div>

  <div class="mashape-endpoint">
    <span class="mashape-endpoint-name">Calculate Income Taxes</span>
   <span class="mashape-endpoint-method mashape-post">POST</span>
   <p>Given an income and filing status, returns dollar amounts of taxes owed.</p>
   <div class="mashape-endpoint-content" data-height="0" style="height: 0px;">
      <span class="mashape-endpoint-route"><span>POST</span> /v1/calculate/{year}</span>
      <div class="mashape-parameter-header">Parameters</div>

                      <div class="mashape-parameter">
                       <span class="mashape-parameter-name">pay_rate</span>
                       <span class="mashape-parameter-type">number</span><span class="mashape-parameter-description">The individual or household income of the person or family.</span>
                       <span class="mashape-parameter-example">Example: 100000</span>
                     </div>

                      <div class="mashape-parameter">
                       <span class="mashape-parameter-name">filing_status</span>
                       <span class="mashape-parameter-type">string</span><span class="mashape-parameter-description">The filing status (either "single", "married", "married_separately", or "head_of_household").</span>
                       <span class="mashape-parameter-example">Example: married</span>
                     </div>

                      <div class="mashape-parameter">
                       <span class="mashape-parameter-name">pay_periods</span>
                       <span class="mashape-parameter-type">number</span><span class="mashape-parameter-description">The number of pay periods in a year.  If not passed, this will default to 1, Taxee will assume the pay_rate value is an annual income, and the results will be amounts owed for an entire year. If a value is passed, the results will be amounts owed per pay period.</span>

                     </div>

                      <div class="mashape-parameter">
                       <span class="mashape-parameter-name">state</span>
                       <span class="mashape-parameter-type">string</span><span class="mashape-parameter-description">The filer's state abbreviation.  If no state is provided, the state income tax amount will not be returned.</span>
                       <span class="mashape-parameter-example">Example: CA</span>
                     </div>

                      <div class="mashape-parameter">
                       <span class="mashape-parameter-name">year</span>
                       <span class="mashape-parameter-type">string</span><span class="mashape-parameter-description">The year of data (tax brackets and deductions) to use when running the calculation.</span>
                       <span class="mashape-parameter-example">Example: 2014</span>
                     </div>



                     <div class="mashape-response-header">Response Example</div>
                     <span class="mashape-example">{
                  "annual": {
                    "state": {
                      "amount": 3804.56
                    },
                    "fica": {
                      "amount": 7650
                    },
                    "federal": {
                      "amount": 13807.5
                    }
                  }
                }</span>

                   </div>
                  </div>

                  <div class="mashape-endpoint mashape">
                    <span class="mashape-endpoint-name">Get Federal Income Tax Information</span>
                   <span class="mashape-endpoint-method mashape-get">GET</span>
                   <p>Given a year, returns tax brackets and deductions for all filing statuses.</p>
                   <div class="mashape-endpoint-content" data-height="0" style="height: 0px;">
                      <span class="mashape-endpoint-route"><span>GET</span> /v1/federal/{year}</span>
                      <div class="mashape-parameter-header">Parameters</div>

                      <div class="mashape-parameter">
                       <span class="mashape-parameter-name">year</span>
                       <span class="mashape-parameter-type">string</span><span class="mashape-parameter-description">The year of the federal income tax information you'd like to request.</span>
                       <span class="mashape-parameter-example">Example: 2014</span>
                     </div>
                     <div class="mashape-response-header">Response Example</div>
                         <span class="mashape-example">{
                          "tax_withholding_percentage_method_tables": {
                            "annual": {
                              "single": {
                                "income_tax_brackets": [
                                  {
                                    "bracket": 0,
                                    "marginal_rate": 10,
                                    "amount": 0
                                  },
                                  {
                                    "bracket": 8925,
                                    "marginal_rate": 15,
                                    "amount": 892.5
                                  },
                                  {
                                    "bracket": 36250,
                                    "marginal_rate": 25,
                                    "amount": 4991.25
                                  },
                                  {
                                    "bracket": 87850,
                                    "marginal_rate": 28,
                                    "amount": 17891.25
                                  },
                                  {
                                    "bracket": 183250,
                                    "marginal_rate": 33,
                                    "amount": 44603.25
                                  },
                                  {
                                    "bracket": 398350,
                                    "marginal_rate": 35,
                                    "amount": 115586.25
                                  },
                                  {
                                    "bracket": 400000,
                                    "marginal_rate": 39.6,
                                    "amount": 116163.75
                                  }
                                ],
                                "deductions": [
                                  {
                                    "deduction_name": "Standard Deduction (Single)",
                                    "deduction_amount": 6100
                                  }
                                ]
                              },
                              "married": {
                                "income_tax_brackets": [
                                  {
                                    "bracket": 0,
                                    "marginal_rate": 10,
                                    "amount": 0
                                  },
                                  {
                                    "bracket": 17850,
                                    "marginal_rate": 15,
                                    "amount": 1785
                                  },
                                  {
                                    "bracket": 72500,
                                    "marginal_rate": 25,
                                    "amount": 9982.5
                                  },
                                  {
                                    "bracket": 146400,
                                    "marginal_rate": 28,
                                    "amount": 28457.5
                                  },
                                  {
                                    "bracket": 223050,
                                    "marginal_rate": 33,
                                    "amount": 49919.5
                                  },
                                  {
                                    "bracket": 398350,
                                    "marginal_rate": 35,
                                    "amount": 107768.5
                                  },
                                  {
                                    "bracket": 450000,
                                    "marginal_rate": 39.6,
                                    "amount": 125846
                                  }
                                ],
                                "deductions": [
                                  {
                                    "deduction_name": "Standard Deduction (Married)",
                                    "deduction_amount": 12200
                                  }
                                ]
                              },
                              "married_separately": {
                                "income_tax_brackets": [
                                  {
                                    "bracket": 0,
                                    "marginal_rate": 10,
                                    "amount": 0
                                  },
                                  {
                                    "bracket": 8925,
                                    "marginal_rate": 15,
                                    "amount": 892.5
                                  },
                                  {
                                    "bracket": 36250,
                                    "marginal_rate": 25,
                                    "amount": 4991.25
                                  },
                                  {
                                    "bracket": 73200,
                                    "marginal_rate": 28,
                                    "amount": 14228.75
                                  },
                                  {
                                    "bracket": 111525,
                                    "marginal_rate": 33,
                                    "amount": 24959.75
                                  },
                                  {
                                    "bracket": 199175,
                                    "marginal_rate": 35,
                                    "amount": 52884.25
                                  },
                                  {
                                    "bracket": 225000,
                                    "marginal_rate": 39.6,
                                    "amount": 62923
                                  }
                                ],
                                "deductions": [
                                  {
                                    "deduction_name": "Standard Deduction (Married Filing Separately)",
                                    "deduction_amount": 6100
                                  }
                                ]
                              },
                              "head_of_household": {
                                "income_tax_brackets": [
                                  {
                                    "bracket": 0,
                                    "marginal_rate": 10,
                                    "amount": 0
                                  },
                                  {
                                    "bracket": 12750,
                                    "marginal_rate": 15,
                                    "amount": 1275
                                  },
                                  {
                                    "bracket": 48600,
                                    "marginal_rate": 25,
                                    "amount": 6652.5
                                  },
                                  {
                                    "bracket": 125450,
                                    "marginal_rate": 28,
                                    "amount": 25865
                                  },
                                  {
                                    "bracket": 203150,
                                    "marginal_rate": 33,
                                    "amount": 47621
                                  },
                                  {
                                    "bracket": 398350,
                                    "marginal_rate": 35,
                                    "amount": 112037
                                  },
                                  {
                                    "bracket": 425000,
                                    "marginal_rate": 39.6,
                                    "amount": 121364.5
                                  }
                                ],
                                "deductions": [
                                  {
                                    "deduction_name": "Standard Deduction (Head of Household)",
                                    "deduction_amount": 8950
                                  }
                                ]
                              }
                            }
                          }
                        }</span>
                       </div>
                      </div>

                      <div class="mashape-endpoint">
                        <span class="mashape-endpoint-name">Get State Income Tax Information</span>
                       <span class="mashape-endpoint-method mashape-get">GET</span>
                       <p>Given a year and a state abbreviation, returns tax brackets and deductions for all filing statuses.</p>
                       <div class="mashape-endpoint-content" data-height="0" style="height: 0px;">
                          <span class="mashape-endpoint-route"><span>GET</span> /v1/state/{year}/{state}</span>
                          <div class="mashape-parameter-header">Parameters</div>

                          <div class="mashape-parameter">
                           <span class="mashape-parameter-name">year</span>
                           <span class="mashape-parameter-type">string</span><span class="mashape-parameter-description">The year of the data you're requesting.</span>
                           <span class="mashape-parameter-example">Example: 2014</span>
                         </div>

                          <div class="mashape-parameter">
                           <span class="mashape-parameter-name">state</span>
                           <span class="mashape-parameter-type">string</span><span class="mashape-parameter-description">The abbreviation of the state whose data you're requesting.</span>
                           <span class="mashape-parameter-example">Example: IL</span>
                         </div>



                         <div class="mashape-response-header">Response Example</div>
                         <span class="mashape-example">{
                      "single": {
                        "income_tax_brackets": [
                          {
                            "bracket": 0,
                            "marginal_rate": 5
                          }
                        ],
                        "special_taxes": [],
                        "deductions": [],
                        "credits": [],
                        "annotations": []
                      },
                      "married": {
                        "income_tax_brackets": [
                          {
                            "bracket": 0,
                            "marginal_rate": 5
                          }
                        ],
                        "special_taxes": [],
                        "deductions": [],
                        "credits": [],
                        "annotations": []
                      },
                      "married_separately": {
                        "income_tax_brackets": [
                          {
                            "bracket": 0,
                            "marginal_rate": 5
                          }
                        ],
                        "special_taxes": [],
                        "deductions": [],
                        "credits": [],
                        "annotations": []
                      },
                      "head_of_household": {
                        "income_tax_brackets": [
                          {
                            "bracket": 0,
                            "marginal_rate": 5
                          }
                        ],
                        "special_taxes": [],
                        "deductions": [],
                        "credits": [],
                        "annotations": []
                      }
                    }</span>

                       </div>
                      </div>

                    </div>
                    </div>
                  </div>

                </div><!-- span3 -->
            </div><!-- row -->
        </div><!-- container -->
    </div><!-- servicewrap -->


  <!-- Contact Section Begins -->
  <section id="contact"><br><br></section>
    <div id="footerwrap">
      <div class="container">
      <br><br><br>
        <div class="row">
          <div class="span6">
            <h3>Contact us!</h3>
            <!--<p>Taxee was developed and is maintained by the good folks at <a href="http://www.nannaroo.com">nannaroo</a>.</p>-->
            <p>Questions or feedback?  Are you using Taxee?  We'd love to hear from you.
                            </br></br><!--<i class="icon-facebook-sign"></i><i class="icon-twitter-sign"></i><i class="icon-pinterest-sign"></i><i class="icon-google-plus-sign"></i><i class="icon-linkedin-sign"></i>-->
                        </p>
          </div><!-- span4 -->
          <div class="span6">
                            <div id="mail-loader">
                                <img src="<?php echo getCDNPath() ?>assets/img/loading.gif" alt="loading..." />
                            </div>
                            <div id="mail-success">
                                <h3>Thanks</h3>
                                <p>We'll holler at you shortly!</p>
                            </div>
                            <form id="mailer">
                                <fieldset>
                                    <div id="form-name" class="control-group">
                                        <div class="controls error">
                                            <input id="input-name" class="span12" type="text" name="name" placeholder="Your Full Name">
                                        </div>
                                    </div>
                                    <div id="form-email" class="control-group">
                                        <div class="controls">
                                            <input id="input-email" class="span12" type="text" name="email" placeholder="Your Email">
                                        </div>
                                    </div>
                                    <div id="form-subject" class="control-group">
                                        <div class="controls">
                                            <input id="input-subject" class="span12" type="text" name="subject" placeholder="Message Subject">
                                        </div>
                                    </div>
                                    <div id="form-body" class="control-group">
                                        <div class="controls">
                                            <textarea id="input-body" class="span12" id="textarea" rows="6" name="body" placeholder="Your Message"></textarea>
                                        </div>
                                    </div>
                                    <button class="btn btn-default pull-left">Send Message</button>

                                </fieldset>
                            </form>
                    </div><!-- span4 -->
        </div><!-- row -->
      </div><!-- container -->
    </div><!-- footerwrap -->


    <!-- Folio  -->
    <section id="folios"></section>

   <!-- <div id="foliowrap">
        <header class="clearfix">
            <div class="container">
                <div class="span12">
                    <div class="boxcolor">
                    </div>
                </div>
            </div>
        </header>
    </div>->

  <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="<?php echo getCDNPath() ?>assets/js/bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo getCDNPath() ?>assets/js/theme.js"></script>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-49491442-1']);
        _gaq.push(['_trackPageview']);

        (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
  </body>
</html>
