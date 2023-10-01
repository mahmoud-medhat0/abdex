<script>
    var form = document.getElementById("checks");
    var checkboxes = form.querySelectorAll('input[type="checkbox"]');
    var hiddenInputsContainer = document.getElementById("hiddenInputsContainer");
    var inputsMap = {};

    function handleCheckboxChange(event) {
      var checkbox = event.target;
      var checkboxId = checkbox.name;
      var orderId = checkboxId.split("-")[1];
      var hiddenInput = inputsMap[orderId];

      if (checkbox.checked) {
        if (!hiddenInput) {
          hiddenInput = createHiddenInput(orderId);
          inputsMap[orderId] = hiddenInput;
        }
        hiddenInput.value = checkbox.value;
      } else {
        if (hiddenInput) {
          hiddenInput.parentNode.removeChild(hiddenInput);
          delete inputsMap[orderId];
        }
      }
    }

    function createHiddenInput(orderId) {
      var hiddenInput = document.createElement("input");
      hiddenInput.type = "hidden";
      hiddenInput.name = "checkbox-" + orderId;
      hiddenInputsContainer.appendChild(hiddenInput);
      return hiddenInput;
    }

    checkboxes.forEach(function (checkbox) {
      checkbox.addEventListener('change', handleCheckboxChange);
    });

    form.addEventListener('submit', function(event) {
      for (var key in inputsMap) {
        if (inputsMap.hasOwnProperty(key)) {
          var hiddenInput = inputsMap[key];
          form.appendChild(hiddenInput);
        }
      }
    });
</script>
@if(session()->get('orders')!=null)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var checkAll = document.getElementById("all-check");
        var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="checkbox-"]');
        var countSpan = document.getElementById("countall");

        function updateCount() {
          var count = 0;
          checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
              count++;
            }
          });
          countSpan.textContent = count;
        }

        // Initially, update the count based on the existing checked checkboxes
        updateCount();

        // Event listener for checkbox change
        document.addEventListener("change", function(event) {
          var target = event.target;
          if (target === checkAll) {
            // Check or uncheck all checkboxes based on the "checkAll" checkbox
            checkboxes.forEach(function(checkbox) {
              checkbox.checked = checkAll.checked;
            });
          } else if (target.matches('input[type="checkbox"][name^="checkbox-"]')) {
            // Update the count whenever a checkbox is checked or unchecked
            updateCount();
          }
        });
      });
</script>
<script>
    var elements = document.querySelectorAll("#all-check");
    elements.forEach(function(element) {
        var parentElement = element.parentNode;
        console.log('1');
        parentElement.id = 'thorder';
        var bre = document.createElement("br");
        parentElement.appendChild(bre);
        var span = document.createElement("span");
        span.id='countall';
        span.textContent = '0';
        parentElement.appendChild(span);
    });
    var countSpan = document.querySelectorAll("#countall");
    $('.selectall').click(function() {
    if ($(this).is(':checked')) {
        selects();
        $('div input').attr('checked', true);
    } else {
        deSelect();
        $('div input').attr('checked', false);
    }
        });
    function selects() {
        var count = 0;
        var countSpan = document.querySelectorAll("#countall");
        @if (session()->get('orders')!=null)
        @foreach (session()->get('orders') as $order)
                var ele = document.getElementsByName('{{ 'checkbox-' . $order->id }}');
                for (var i = 0; i < ele.length; i++) {
                    if (ele[i].type == 'checkbox')
                        ele[i].checked = true;
                        count++;
                        countSpan.forEach(function(e){
                            e.textContent = count;
                        });
                }
            @endforeach
        @endif
        }

        function deSelect() {
            var countSpan = document.querySelectorAll("#countall");
            @if (session()->get('orders')!=null)
            @foreach (session()->get('orders') as $order)
                var ele = document.getElementsByName('{{ 'checkbox-' . $order->id }}');
                for (var i = 0; i < ele.length; i++) {
                    if (ele[i].type == 'checkbox')
                        ele[i].checked = false;
                }
            @endforeach
            countSpan.forEach(function(e){
                e.textContent = 0;
            });
            @endif
        }
</script>
@endif

