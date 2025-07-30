# Contributing to Laravel Bits

Thank you for considering contributing to Laravel Bits! We welcome contributions from the community and are pleased to have you join us.

## Code of Conduct

This project and everyone participating in it is governed by our commitment to creating a welcoming and inclusive environment. Please be respectful and constructive in all interactions.

## How to Contribute

### Reporting Issues

Before creating an issue, please:

1. **Search existing issues** to avoid duplicates
2. **Use a clear and descriptive title** for the issue
3. **Describe the exact steps** to reproduce the problem
4. **Provide specific examples** to demonstrate the steps
5. **Include relevant details** about your configuration and environment

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, please:

1. **Use a clear and descriptive title**
2. **Provide a step-by-step description** of the suggested enhancement
3. **Explain why this enhancement would be useful** to most Laravel Bits users
4. **Include examples** of how the enhancement would be used

### Development Setup

1. **Fork the repository** on GitHub
2. **Clone your fork** locally:
   ```bash
   git clone https://github.com/your-username/laravel-bits.git
   cd laravel-bits
   ```
3. **Install dependencies**:
   ```bash
   composer install
   ```
4. **Create a feature branch**:
   ```bash
   git checkout -b feature/your-feature-name
   ```

### Running Tests

Before submitting your changes, make sure all tests pass:

```bash
# Run the test suite
composer test

# Run tests with coverage
composer test -- --coverage

# Run static analysis
composer lint
```

### Coding Standards

This project follows PSR-12 coding standards and uses Laravel Pint for code formatting:

```bash
# Format code automatically
./vendor/bin/pint

# Check code style without fixing
./vendor/bin/pint --test
```

### Writing Tests

- **All new features must include tests**
- **Bug fixes should include regression tests**
- **Tests should be placed in the appropriate directory** under `tests/`
- **Use descriptive test names** that explain what is being tested
- **Follow the existing test patterns** in the codebase

Example test structure:
```php
<?php

test('it can do something useful', function () {
    // Arrange
    $input = 'test data';
    
    // Act
    $result = someFunction($input);
    
    // Assert
    expect($result)->toBe('expected output');
});
```

### Pull Request Process

1. **Update documentation** if you're changing functionality
2. **Add tests** for any new features or bug fixes
3. **Ensure all tests pass** and code follows our standards
4. **Update the README.md** if you're adding new traits or macros
5. **Create a pull request** with a clear title and description

#### Pull Request Guidelines

- **Use a clear and descriptive title**
- **Reference any related issues** using keywords like "fixes #123"
- **Describe your changes** in detail
- **Include motivation and context** for the changes
- **List any breaking changes**

### Adding New Features

When adding new traits or macros:

1. **Create the feature** in the appropriate directory (`src/Traits/` or `src/Macros/`)
2. **Add comprehensive tests** in the `tests/Unit/` directory
3. **Update the README.md** with documentation and examples
4. **Ensure the feature follows Laravel conventions**
5. **Add appropriate type hints and docblocks**

### Documentation

- **Keep the README.md up to date** with any new features
- **Include practical examples** in your documentation
- **Use clear and concise language**
- **Follow the existing documentation style**

## Development Guidelines

### Traits

- Traits should be focused and have a single responsibility
- Include comprehensive docblocks for all public methods
- Provide practical examples in tests
- Consider backward compatibility

### Macros

- Macros should extend Laravel's existing functionality naturally
- Follow Laravel's naming conventions
- Include proper parameter validation
- Test edge cases thoroughly

### Code Quality

- **Write clean, readable code**
- **Use meaningful variable and method names**
- **Keep methods focused and small**
- **Add comments for complex logic**
- **Follow SOLID principles**

## Getting Help

If you need help with contributing:

1. **Check the existing documentation** and tests for examples
2. **Look at recent pull requests** to see how others have contributed
3. **Create an issue** to discuss your proposed changes before implementing them
4. **Reach out to maintainers** if you have questions

## Recognition

Contributors will be recognized in the project's README.md file. We appreciate all contributions, whether they're bug reports, feature requests, documentation improvements, or code contributions.

Thank you for contributing to Laravel Bits!