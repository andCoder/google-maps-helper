/**
 * Created by Admin on 13.04.2017.
 */

function TemplateParser() {
    var template = null;

    this.getTemplate = function () {
        return template;
    };

    this.setTemplate = function (text) {
        template = text;
    };
}


TemplateParser.prototype.parse = function (data) {
    var template = this.getTemplate();
    if (template != null) {
        var exp = /\%(\w+)\%/g;
        var result = null;
        template = template.replace(exp, function (searchText) {
            var field = 'gmh_' + searchText.substring(1, searchText.length - 1);
            var result = searchText;
            if (data.hasOwnProperty(field)) {
                result = data[field];
            }
            return result;
        });
    }
    return template;
};