/**
 * Queries the FanFeedr Docs DOM at http://developer.fanfeedr.com/docs and builds a json hash 
 * that can be used for easily generating API clients in any language.
 */
jQuery.getScript('http://jquery-json.googlecode.com/files/jquery.json-2.2.min.js', function(){
        var response = {};
    var out = [];
    var $j = jQuery;
    
    var methods = $j('div.method');
    
    methods.each(function(){
        var m = $j.trim($j(this).find('h3').text());
        var d = $j.trim($j(this).find('.method_description').html());
        var e = null;
    
        var ex_hid = $j(this).find('input[type=hidden]');
        if(ex_hid.length > 0){
            e = ex_hid.val();
        }
        var t = $j(this).find('table.method_params');
        var trs = t.find('tr:gt(0)');
        var p = [];
    
        trs.each(function(){
            var el = $j(this);
            var de = null;
            var d_c = $j.trim(el.find('td:eq(2)').text());
                
            if(d_c.length > 0){
                de = d_c;
            }
            var t = $j.trim(el.find('td:eq(3)').text());
            var ty = '';
            var ty_primitive_def = null;
            if(t=='string'){
                ty = 'string';
            } else if(t=='int'){
                ty = 'i32';
            } else if(t.toLowerCase()=='resource'){
                ty = 'resource';
                ty_primitive_def = 'string';
            } else if(t=='decimal'){
                ty = 'double';
            } else{
                ty = t;
            }
            p.push({
                'name': el.find('td:eq(0)').text(), 
                'required': ($j.trim(el.find('td:eq(1)').text())=='Y'),
                'default': de,
                'type': ty,
                'type_primitive_def': ty_primitive_def
            });
        });
    
    
        var o = {'method':m,'description':d, 'example_data': e, 'parameters': p}
        out.push(o);
    });
    
    response.methods = out;
    
    $j('body').append('<div><textarea>'+jQuery.toJSON(response).replace(/\\/g,'').replace(']"', ']').replace('"[', '[')+'</textarea></div>');
});
