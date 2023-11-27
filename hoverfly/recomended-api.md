# Recommendation API

GET /api/v1/book/{book_id}/recommendation
Authorization: Bearer {token}

## 200

```json
{
    "id": 1,
    "ts": 1234565,
    "recommendations": [
        {"id": 1}
    ]
}
```

## 403

```json
{
    "error": "access denied"
}
```
