## 題目一
```sql
select * from orders where currency='twd' order by amount desc limit 0,5;
```
## 題目二
* 使用 `explain` 分析
* 檢查索引
    * 根據 `explain` 的結果，是否有效使用索引
    * 建立 currency, amount 索引
    * 再次執行查詢，type 從 ALL 改為 range，表示已經使用索引

## API 實作 - 說明 SOLID 與 設計模式
### SOLID 原則
* 單一職責原則
  * `ApiController` 負責處理與訂單相關的 HTTP 請求，它只專注於一項職責。

* 依賴反轉原則
  * 透過 `__construct` 注入 `OrderService`，`ApiController` 依賴於 `OrderService` 的抽象而不是具體物件。

### 設計模式
* 依賴注入
  * 透過 `__construct` 將 `OrderService` 的實例注入到 `ApiController` 中，依賴注入有助於減少類之間的耦合度，並增加程式的靈活性和可測試性。


