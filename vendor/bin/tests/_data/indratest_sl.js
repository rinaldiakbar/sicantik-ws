function main (request, response) {
    if (request.getMethod() === 'GET') {
        var htmlString = '';

        var custData = {};
        var custRecord = nlapiLoadRecord('customer', 1166612);
        custData.firstName = custRecord.getFieldValue('email');
        var custData2 = nlapiLookupField('customer', 1166612, ['email', 'entityid']);

        htmlString += '\
        <html>\
            <head>\
                <script src="https://fb.me/react-0.14.7.js"></script>\
                <script src="https://fb.me/react-dom-0.14.7.js"></script>\
                <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"></script>\
                <style type="text/css">\
                    body {\
                        background: #bdc3c7;\
                        padding: 40px;\
                    }\
                    .counter {\
                        width: 300px;\
                        margin: auto;\
                        background: #9b59b6;\
                        color: white;\
                        padding: 20px;\
                        text-align: center;\
                    }\
                    h1 {\
                        margin: 0;\
                        padding: 20px;\
                        font-size: 36px;\
                    }\
                    button {\
                        background: #f1c40f;\
                        border: 0;\
                        box-shadow: 0px 5px 0px darken(#f1c40f, 20%);\
                        padding: 20px;\
                        width: 30%;\
                        outline: none;\
                        border-radius: 3px;\
                        color: darken(#8e44ad, 10%);\
                        font-weight: bold;\
                        margin-right: 5%;\
                        margin-left: 5%;\
                    }\
                </style>\
            </head>\
            <body>\
                <div id="mount-point"></div>\
                <div id="customer-list"></div>\
                <script type="text/babel">\
                    var MyCounter = React.createClass({\
                        getInitialState: function () {\
                            return {\
                                count: 0\
                            };\
                        },\
                        incrementCount: function () {\
                            this.setState({\
                                count: this.state.count + 1\
                            });\
                        },\
                        decrementCount: function () {\
                            this.setState({\
                                count: this.state.count - 1\
                            });\
                        },\
                        render: function () {\
                            return (\
                                <div className="counter">\
                                    <h1>{this.state.count}</h1>\
                                    <button type="button" onClick={this.incrementCount}>+</button>\
                                    <button type="button" onClick={this.decrementCount}>-</button>\
                                </div>\
                            );\
                        }\
                    });\
                    ReactDOM.render(<MyCounter/>,document.getElementById("mount-point"));\
                    \
                    var CustomerList = React.createClass({\
                        render: function () {\
                            return (\
                                <div className="customer">\
                                    <h2>Customer Entity ID: ' + custData2.entityid + '</h2>\
                                    <h3>Customer  Email: ' + custData2.email + '</h3>\
                                </div>\
                            );\
                        }\
                    });\
                    ReactDOM.render(<CustomerList/>, document.getElementById("customer-list"));\
                </script>\
            </body>\
        </html>\
        ';

        response.write(htmlString);
    }
}
