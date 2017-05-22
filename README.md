# LaravelAuto
a Laravel helper package to make ~~all~~ almost everything in your projects

[![Latest Version](https://img.shields.io/github/release/maikealame/laravel-auto.svg?style=flat-square)](https://github.com/maikealame/laravel-auto/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/maikealame/laravel-auto.svg?style=flat-square)](https://packagist.org/packages/maikealame/laravel-auto)

- Create automatically sql where conditions (mysql and postgresql support)
- Create list filters functionality with flexible code for your own layout
- Create sortable functionality with flexible code for your own layout
- Create pagination to combine with this package methods
- Create list length combobox

*Want more ? send feedback and we increase this package with more magics.*

## What it do ?

Filter / Sort / Paginate your tables with this light code:

`$users = \App\User::autoWhere()->autoSort()->autoPaginate();`

Can I use Eloquent where too ? Yes

`$users = \App\User::where('age','>=',10)->autoWhere()->autoSort()->autoPaginate();`

And what this autoWhere do ?

- if url is ***http://localhost/usuarios?filter[age]=>20*** the query will be:

`select * from users where age > 20;`

- if url is ***http://localhost/usuarios?filter[age]=10:20*** the query will be:

`select * from users where age >= 10 AND age <= 20;`

- if url is ***http://localhost/usuarios?filter[age]=<>20*** the query will be:

`select * from users where age <> 10;`

- if url is ***http://localhost/usuarios?filter[name]=Maike*** the query will be:

`select * from users where UPPER(name) LIKE '%MAIKE%' ;`

- if url is ***http://localhost/usuarios?filter[name]=Maike&columns[name]=equal*** the query will be:

`select * from users where name = 'Maike' ;`

- if url is ***http://localhost/usuarios?filter[name]=Maike&columns[name]=text_equal*** the query will be:

`select * from users where UPPER(name) = 'MAIKE' ;`

- if url is ***http://localhost/usuarios?filter[birth]=12/12/1990*** the query will be:

`select * from users where birth = '1990-12-12' ;`

- if url is ***http://localhost/usuarios?filter[birth]=<12/12/1990*** the query will be:

`select * from users where birth < '1990-12-12' ;`

- if url is ***http://localhost/usuarios?filter[birth]=12/12/1990|*** the query will be:

`select * from users where birth >= '1990-12-12' ;`

- if url is ***http://localhost/usuarios?filter[birth]=12/12/1990|12/12/2000*** the query will be:

`select * from users where birth between '1990-12-12' AND '2000-12-12' ;`

- if url is ***http://localhost/usuarios?filter[bool_field]=1*** the query will be:

- if url is ***http://localhost/usuarios?filter[bool_field]=true*** the query will be:

`select * from users where bool_field = true;`

- if url is ***http://localhost/usuarios?filter[bool_field]=0*** the query will be:

- if url is ***http://localhost/usuarios?filter[bool_field]=false*** the query will be:

`select * from users where bool_field = false;`

- if url is ***http://localhost/usuarios?filter[birth]=1&columns[birth]=null*** the query will be:

`select * from users where birth IS NULL;`

- if url is ***http://localhost/usuarios?filter[birth]=&columns[birth]=null*** the query will be:

`select * from users where birth IS NOT NULL;`

- if url is ***http://localhost/usuarios?filter[perfil][]=1&filter[perfil][]=2*** the query will be:

`select * from users where (perfil = 1 OR perfil = 2) ;`


**Seriosly, don't worry with type of column and sql syntax, this will automate your querys with fresh and clean code.**


## What is necessary ?

To work all modules, it's necessary the following dependencies:

- Laravel 5.*
- Font-awesome
- Bootstrap
- Jquery

## Wiki ?

Soon...


## Example ?

Just for now

`[url] http://localhost/ocorrencias?sort=id&order=desc&filter[t.protocol]=<7`


![table image](https://raw.githubusercontent.com/maikealame/laravel-auto/master/docs/ex.PNG)

## How works ?

### [view]

Create your table with bootstrap or not, use all your logic with blade like normally, and use the blade directives of the package:

- *Generate a script code to filter table without form, pass in param the selector of button will trigger your filter and inputs*

`@autowherescript('.btn-filter', '.input-filter')`

**param[0]: button selector** - default: '.btn-filter'

**param[1]: text inside `<a>`** - default: '.input-filter'

---

- *Generate a script code to async the reload table when paging, pass in 1st param many selectors elements will you want replace. In 2nd param the flag to change URL with page reloaded*

`@autopagesasync('.panel-table')`

`@autopagesasync(['.panel-table tbody', '.panel-table .panel-footer'])`

`@autopagesasync(['.panel-table tbody', '.panel-table .panel-footer'], false)`

**param[0]: replace selector** - required, string or array

**param[1]: url change flag** - default: true

---

**Usage:**

```
<head>
@autowherescript('.btn-filter', '.input-filter')
@autopagesasync('.panel-table')
</head>
```

---

- *Call column header title, when click sort this column will happen.*

`@autosort('name','Nome')`

**param[0]: column table** - required, can bring sql alias

**param[1]: text inside `<a>`** - not required

---

- *Bring the value previously used in filter to input tag value, to not loss when reload page*

`@autowherefilter('name')`

`@autowherefilter('id', 1)`

`@autowherefilter('id', 1, "checked")`

*Can pass 2nd param, thats indicates the tag is a select option. What to do? pass the option value in 2nd param and the return is "selected" if you filter with that option selected*

*You can overwrite "selected" return to other string, like "checked" when pass as 3rd param*

---

- *Get a select tag with length of pagination, when change reload table with new length*

`@autopageslength($tickets)`

**param[0]: Paginator** - use an Eloquent or QueryBuilder and finish the query with paginate() to get Paginator

- *Get the pagination buttons*

`@autopages($tickets)`

**param[0]: Paginator** - use an Eloquent or QueryBuilder and finish the query with paginate() to get Paginator

---

**Usage:**

```
<div class="panel panel-table">
    <div class="panel-heading">
        <h3 class="panel-title">
            Registros
        </h3>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr role="row">
                <th>@autosort('t.id','Protocolo')</th>
                <th>@autosort('t.start','Data Abertura')</th>
                <th>@autosort('t.end','Data Fechamento')</th>
                <th>@autosort('p.name','Portfolio')</th>
                <th class="tc"><b class="fa fa-gear"></b></th>
            </tr>
            <tr>
                <th><input type="text" class="form-control" name="t.id" value="@autowherefilter('t.id')"></th>
                <th><input type="text" class="form-control datepicker" name="t.start" value="@autowherefilter('t.start')"></th>
                <th><input type="text" class="form-control datepicker" name="t.end" value="@autowherefilter('t.end')"></th>
                <th>
                    <select name="p.id" multiple>
                        <option value="1" @autowherefilter('p.id',1)>Portfolio 1</option
                        <option value="2" @autowherefilter('p.id',2)>Portfolio 2</option
                        <option value="3" @autowherefilter('p.id',3)>Portfolio 3</option
                    </select>
                </th>
                <th class="tc"><button class="btn btn-primary btn-filter"><i class="fa fa-search"></i></button></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{$ticket->protocol}}</td>
                    <td>{{$ticket->start}}</td>
                    <td>{{$ticket->end}}</td>
                    <td>{{$ticket->portfolio}}</td>
                    <td class="tc">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="panel-footer clearfix">
        <div class="col pull-left">
            Mostrando @autopageslength($tickets) de {{$tickets->total()}}
        </div>
        <div class="col pull-right">
            @autopages($tickets)
        </div>
    </div>
</div>
```


### [Controller]

Let's see it:
```
use App\Http\Controllers\Controller;
use Auto\Facades\Auto;

class TestsController extends Controller
{

    // without joins
    
    public function autowhere(){
        $tickets = \App\Ticket::autoWhere()->autoSort()->autoPaginate();
        return view("tests.ticket",compact("tickets"));
    }


    // with joins
    
    public function autowherealias()
    {
        $tickets = \App\Ticket::from('ticket as t')
            ->select('t.id as id',
                't.start as start',
                't.end as end',
                'p.name as portfolio'
            )
            ->leftJoin('portfolio as p', 't.portfolio_id', '=', 'p.id')
            ->autoWhere()
            ->autoSort()
            ->autoPaginate();
        return view("tests.ticket_alias", compact("tickets"));
    }
    
    
    // with overwrite column type
    
    public function autowhereoverwrite()
    {
        $tickets = \App\Ticket::from('ticket as t')
            ->select('t.id as id',
                't.start as start',
                't.end as end',
                'p.name as portfolio'
            )
            ->leftJoin('portfolio as p', 't.portfolio_id', '=', 'p.id')
            ->autoWhere([ 'columns' =>[ "p.name" => "equal" ] ]) // you can pass param here and overwrite the type of column to other
            ->autoSort()
            ->autoPaginate();
        return view("tests.ticket_alias", compact("tickets"));
    }
    
    
    // with 'or' conditions - Ex. url: localhost/ocorrencias?filter[t.start]=21/05/2017&filter[t.end]=21/05/2017
    
    public function autowhereor()
    {
        $tickets = \App\Ticket::from('ticket as t')
            ->select('t.id as id',
                't.start as start',
                't.end as end',
                'p.name as portfolio'
            )
            ->leftJoin('portfolio as p', 't.portfolio_id', '=', 'p.id')
            ->autoWhere([ 'or' =>[ "t.start", "t.end" ] ]) // you can pass param here and create 'or' conditions with columns you wish
            ->autoSort()
            ->autoPaginate();
        // generate where: ' where ( t.start = 2017-05-21 OR t.end = 2017-05-21 ) '
        return view("tests.ticket_alias", compact("tickets"));
    }
    
    
    // set default sort params if not has in url order,sort params
    // this overwrite param setted in config file
    
    public function autowheresort(){
        $tickets = \App\Ticket::autoWhere()->autoSort( [ "id", "desc" ] )->autoPaginate(); // set columns and 'asc' or 'desc'
        return view("tests.ticket",compact("tickets"));
    }
    
    
    // set default pagination length if not has in url length params
    // this overwrite param setted in config file
    
    public function autowherepaginate(){
        $tickets = \App\Ticket::autoWhere()->autoSort()->autoPaginate( 10 ); // set columns and 'asc' or 'desc'
        return view("tests.ticket",compact("tickets"));
    }
    
    
    // set default value to query column
    
    public function autowheredefaultvalue(){
        Auto::setField( 't.start', date("Y-m-d") );
        $tickets = \App\Ticket::autoWhere()->autoSort()->autoPaginate(); // automatically get new field defaut value in Request filter param
        return view("tests.ticket",compact("tickets"));
    }
}
```

Ok, where `autoWhere`, `autoSort` and `autoPaginate` come from ? See [**Model**] section to understand.
It's very simple and light for your code, even I don't believe.

### [Model]

What happens here is simple too:

```
use Auto\AutoWhere;
use Auto\AutoSort;
use Auto\AutoPaginate;

class Ticket extends Model
{
    use AutoWhere, AutoSort, AutoPaginate;
    ...
}
```

Just using traits, We manipulate the QueryBuilder until we get the expected result. Of course, with other magics.


## How to install ?

Now the times arrived. check out:

1. Download package with Composer

```
$ composer require maikealame/laravel-auto
```

Check the [`composer.json`]

```
"require": {
  ...
  "maikealame/laravel-auto": "*"
}
```

2. Add this package to your application service providers in `config/app.php`

```
'providers' => [
    ...
    App\Providers\RouteServiceProvider::class,

    /*
     * Third Party Service Providers...
     */
    Auto\AutoServiceProvider::class,
],
```

3. Publish the package configuration file to your application.

```
$ php artisan vendor:publish --provider="Auto\AutoServiceProvider" --tag="config"
```

See file in `config/laravelauto.php`

---

Now we done, take a coffe and start code.

**Any feedback it's welcome, issues are here for it.**
