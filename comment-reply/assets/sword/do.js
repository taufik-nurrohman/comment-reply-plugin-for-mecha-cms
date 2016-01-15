(function(w, d) {
    var form = d.getElementById('comment-form'),
        form_parent = form.parentNode,
        form_x = d.getElementsByClassName('btn-reject-reply')[0],
        comment = d.getElementsByClassName('comments-body')[0].children,
        parent_o = form.parent.value,
        message_o = 'placeholder' in form.message ? form.message.placeholder : "",
        do_reply = function(elem) {
            elem.onclick = function() {
                this.parentNode.parentNode.appendChild(form);
                form.parent.value = this.getAttribute('data-parent');
                form.message.placeholder = this.title;
                form.message.focus();
                form_x.style.display = "";
                return false;
            };
        };
    for (var i = 0, len = comment.length; i < len; ++i) {
        do_reply(comment[i].getElementsByClassName('a-reply')[0]);
    }
    form_x.onclick = function() {
        var c = /[?&;]reply=/.test(w.location.search) && form.parentNode === form_parent;
        form_parent.appendChild(form);
        form.parent.value = parent_o;
        form.message.placeholder = message_o;
        form.message.focus();
        this.style.display = 'none';
        return c;
    };
})(window, document);