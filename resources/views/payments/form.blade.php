 <div class="mb-3">
     <div class="mb-3">
         {!! Form::label("text", "Popis platby", ["class" => "form-label"]) !!}
         {!! Form::text("title", $data->title ?? null, ["placeholder" => "Učebnice angličtiny", "class" => "form-control", "required" => true]) !!}
     </div>
     <div class="mb-3">
         {!! Form::label("text", "Částka", ["class" => "form-label"]) !!}
         {!! Form::number("amount", $data->amount ?? null, ["placeholder" => "200", "class" => "form-control", "required" => true]) !!}
     </div>
     <div class="mb-3">
         {!! Form::label("text", "Plátce", ["class" => "form-label"]) !!}
         {!! Form::select("payer[]", [], null, ["id" => "payer", "data-placeholder" => "Aleš Medek nebo m2019", "class" => "form-control", "required" => true, "multiple" => true, "data-select2-enable" => true]) !!}
     </div>
     <div class="mb-3">
         {!! Form::label("text", "Splatnost", ["class" => "form-label"]) !!}
         {!! Form::input("date", "due", $data->due ?? null, ["placeholder" => "2022-01-02 13:00:00", "class" => "form-control", "required" => true]) !!}
     </div>
     <div class="mb-3">
         {!! Form::button($formButtonTitle, ["type" => "submit", "class" => "btn btn-primary"]) !!}
     </div>

 </div>
