$(document).ready(function() {
    var calcimages = new Array("hi", "hello", "hiho", "boobs")
    var currentrandom = 0;

    var timer = setInterval(function() {
        var newrandom = currentrandom;
        while (currentrandom == newrandom) {
            newrandom = getRandom(0, calcimages.length - 1);
        }
        currentrandom = newrandom;
        var img = calcimages[getRandom(0, calcimages.length - 1)];
        $("#calculator-img").attr("src", "assets/img/calculator/" + img + ".png");
    }, 2000);

    $("#testtaxee").bind("click", getUserStory);

    $("#mailer").submit(function(event) {
        var fields = new Array("name", "email", "subject", "body");
        var invalidFieldCount = 0;
        for (var prop in fields) {
            var field = fields[prop];
            var fieldVal = $("#input-" + field).val();
            if (fieldVal == "") {
                invalidFieldCount++;
                $("#form-" + field).addClass("error");
            } else {
                if (field == "email") {
                    if (validateEmail(fieldVal)) {
                        $("#form-" + field).removeClass("error");
                    } else {
                        invalidFieldCount++;
                        $("#form-" + field).addClass("error");

                    }
                } else {
                    $("#form-" + field).removeClass("error");
                }
            }
        }
        if (invalidFieldCount > 0) {
            return false;
        }
        var info = $(this).serialize();
        $(this).hide();
        $("#mail-loader").show();
        var request = $.ajax({
            url: "mailer.php",
            type: "POST",
            data: info,
            dataType: "html",
            complete: mailsent
        });

        event.preventDefault();
    });

    $(".mashape-endpoint-content").removeAttr("style").hide();
    $(".mashape-endpoint").bind("click", function(e) {
        $($(this).children(".mashape-endpoint-content")[0]).toggle();
    });

});

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function mailsent(e) {
    $("#mail-loader").hide();
    $("#mail-success").show();
}

function getUserStory(e) {
    $(e.currentTarget).button('loading');
    //$().button('loading')
    var spousepromise = $.ajax({
        url: 'http://api.randomuser.me/',
        dataType: 'json',
        cache: false
    });

    /*var userpromise = $.ajax({
      url: 'http://taxee:8080',
      dataType: 'json'
    });*/
    spousepromise.then(function(response1) {
        var spouse = response1.results[0].user;
        var spousemodifier;
        var ssrandomizer = getRandom(0, 99);
        if (ssrandomizer > 85) {
            spousemodifier = "?gender=" + spouse.gender;
        } else {
            if (spouse.gender == "female") {
                spousemodifier = "?gender=male";
            } else {
                spousemodifier = "?gender=female";
            }
        }

        var userpromise = $.ajax({
            url: 'http://api.randomuser.me/' + spousemodifier,
            dataType: 'json',
            cache: false
        });

        userpromise.then(function(response) {
            var persondata = response.results[0].user;
            $("#avatar").attr("src", persondata.picture);
            if (persondata.location.state == "iawaii") {
                persondata.location.state = "hawaii";
            }

            var openers = new Array("Say hi to", "Meet", "This is", "Here is", "This cool cat is");
            var pronoun = (persondata.gender == "female") ? "she" : "he";
            var possessive = (persondata.gender == "female") ? "her" : "his";
            var married = (getRandom(0, 1) == 0) ? true : false;
            var opener = openers[getRandom(0, openers.length - 1)];
            var spousetype = (spouse.gender == "female") ? "wife" : "husband";
            var income = Math.round((getRandom(8000, 120000) / 100)) * 100;
            var story = opener + " " + convert_case(persondata.name.first) + " " + convert_case(persondata.name.last) + ".  ";
            story += convert_case(pronoun) + "'s " + getAge(persondata.dob) + ", and lives in " + convert_case(persondata.location.state);
            if (married) {
                story += " with " + possessive + " " + spousetype + " " + convert_case(spouse.name.first);
                income = income * 2;
            }
            story += ".  "
            var poststring = "pay_rate=" + income + "&";
            poststring += "pay_periods=1&";
            if (married) {
                poststring += "filing_status=married&";
            } else {
                poststring += "filing_status=single&";
            }
            poststring += "state=" + convert_state(persondata.location.state, "abbrev");
            var taxeepromise = $.ajax({
                url: BASE_URL + "api/v1/calculate/2014",
                type: "POST",
                dataType: 'json',
                cache: false,
                data: poststring
            });

            taxeepromise.then(function(response) {
                if (married) {
                    story += "Their household income is $" + numberWithCommas(income) + ".  ";
                    story += "<br /><br /> <b>Taxee</b> calculates that the federal income tax " + pronoun + " and " + possessive + " spouse will owe is <b>$" + numberWithCommas(response.annual.federal.amount.toFixed(2)) + "</b>";
                    if (response.annual.state.amount != null) {
                        story += ", and they'll owe <b>$" + numberWithCommas(response.annual.state.amount.toFixed(2)) + "</b> to the State of " + convert_case(persondata.location.state) + ".";
                    } else {
                        story += ".  " + convert_case(persondata.location.state) + " doesn't collect income tax, so he owes them nothing.";
                    }

                } else {
                    story += convert_case(possessive) + " annual income is $" + numberWithCommas(income) + ".  ";
                    story += "<br /><br /> <b>Taxee</b> calculates that the federal income tax " + pronoun + "'ll owe is <b>$" + numberWithCommas(response.annual.federal.amount.toFixed(2)) + "</b>";
                    if (response.annual.state.amount != null) {
                        story += ", and " + pronoun + "'ll owe <b>$" + numberWithCommas(response.annual.state.amount.toFixed(2)) + "</b> to the State of " + convert_case(persondata.location.state);
                        story += ".";
                    } else {
                        story += ".  " + convert_case(persondata.location.state) + " doesn't collect income tax, so he owes them nothing.";
                    }

                }
                $("#user-story-inner").html(story);
                $("#user-block").show();
                $(e.currentTarget).button('reset');
            });

        });
    });
}

function getRandom(min, max) {
    return Math.round(Math.random() * (max - min) + min);
}

function convert_case(str) {
    return str.toLowerCase().replace(/(^| )(\w)/g, function(x) {
        return x.toUpperCase();
    });
}

function getAge(time) {
    var date = new Date(time * 1000);
    var today = new Date();
    return today.getFullYear() - date.getFullYear();
}

function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}

function convert_state(name, to) {
    var states = new Array({
        'name': 'Alabama',
        'abbrev': 'AL'
    }, {
        'name': 'Alaska',
        'abbrev': 'AK'
    }, {
        'name': 'Arizona',
        'abbrev': 'AZ'
    }, {
        'name': 'Arkansas',
        'abbrev': 'AR'
    }, {
        'name': 'California',
        'abbrev': 'CA'
    }, {
        'name': 'Colorado',
        'abbrev': 'CO'
    }, {
        'name': 'Connecticut',
        'abbrev': 'CT'
    }, {
        'name': 'Delaware',
        'abbrev': 'DE'
    }, {
        'name': 'Florida',
        'abbrev': 'FL'
    }, {
        'name': 'Georgia',
        'abbrev': 'GA'
    }, {
        'name': 'Hawaii',
        'abbrev': 'HI'
    }, {
        'name': 'Idaho',
        'abbrev': 'ID'
    }, {
        'name': 'Illinois',
        'abbrev': 'IL'
    }, {
        'name': 'Indiana',
        'abbrev': 'IN'
    }, {
        'name': 'Iowa',
        'abbrev': 'IA'
    }, {
        'name': 'Kansas',
        'abbrev': 'KS'
    }, {
        'name': 'Kentucky',
        'abbrev': 'KY'
    }, {
        'name': 'Louisiana',
        'abbrev': 'LA'
    }, {
        'name': 'Maine',
        'abbrev': 'ME'
    }, {
        'name': 'Maryland',
        'abbrev': 'MD'
    }, {
        'name': 'Massachusetts',
        'abbrev': 'MA'
    }, {
        'name': 'Michigan',
        'abbrev': 'MI'
    }, {
        'name': 'Minnesota',
        'abbrev': 'MN'
    }, {
        'name': 'Mississippi',
        'abbrev': 'MS'
    }, {
        'name': 'Missouri',
        'abbrev': 'MO'
    }, {
        'name': 'Montana',
        'abbrev': 'MT'
    }, {
        'name': 'Nebraska',
        'abbrev': 'NE'
    }, {
        'name': 'Nevada',
        'abbrev': 'NV'
    }, {
        'name': 'New Hampshire',
        'abbrev': 'NH'
    }, {
        'name': 'New Jersey',
        'abbrev': 'NJ'
    }, {
        'name': 'New Mexico',
        'abbrev': 'NM'
    }, {
        'name': 'New York',
        'abbrev': 'NY'
    }, {
        'name': 'North Carolina',
        'abbrev': 'NC'
    }, {
        'name': 'North Dakota',
        'abbrev': 'ND'
    }, {
        'name': 'Ohio',
        'abbrev': 'OH'
    }, {
        'name': 'Oklahoma',
        'abbrev': 'OK'
    }, {
        'name': 'Oregon',
        'abbrev': 'OR'
    }, {
        'name': 'Pennsylvania',
        'abbrev': 'PA'
    }, {
        'name': 'Rhode Island',
        'abbrev': 'RI'
    }, {
        'name': 'South Carolina',
        'abbrev': 'SC'
    }, {
        'name': 'South Dakota',
        'abbrev': 'SD'
    }, {
        'name': 'Tennessee',
        'abbrev': 'TN'
    }, {
        'name': 'Texas',
        'abbrev': 'TX'
    }, {
        'name': 'Utah',
        'abbrev': 'UT'
    }, {
        'name': 'Vermont',
        'abbrev': 'VT'
    }, {
        'name': 'Virginia',
        'abbrev': 'VA'
    }, {
        'name': 'Washington',
        'abbrev': 'WA'
    }, {
        'name': 'West Virginia',
        'abbrev': 'WV'
    }, {
        'name': 'Wisconsin',
        'abbrev': 'WI'
    }, {
        'name': 'Wyoming',
        'abbrev': 'WY'
    });
    var returnthis = false;
    $.each(states, function(index, value) {
        if (to == 'name') {
            if (value.abbrev.toLowerCase() == name.toLowerCase()) {
                returnthis = value.name;
                return false;
            }
        } else if (to == 'abbrev') {
            if (value.name.toLowerCase() == name.toLowerCase()) {
                returnthis = value.abbrev.toUpperCase();
                return false;
            }
        }
    });
    return returnthis;
}